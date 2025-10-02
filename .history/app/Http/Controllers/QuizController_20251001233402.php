<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ScoreLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function Confirmation(){
        return view('Quiz.confirmation');
    }

    public function index()
    {
        // Check if quiz already exists in storage
        $quizPath = 'quiz/current_quiz.json';
        
        if (Storage::exists($quizPath)) {
            $quizData = json_decode(Storage::get($quizPath), true);
        } else {
            // Generate new quiz using Gemini
            $quizData = $this->generateQuizWithGemini();
            
            if ($quizData) {
                Storage::put($quizPath, json_encode($quizData));
            } else {
                return back()->with('error', 'Failed to generate quiz. Please try again.');
            }
        }

        return view('Quiz.index', ['quiz' => $quizData]);
    }

    private function generateQuizWithGemini()
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            return null;
        }

        $prompt = "Generate a quiz with 10 multiple choice questions. Each question should have 4 options (A, B, C, D) with only one correct answer. Return the response in valid JSON format with this exact structure:
        {
            \"title\": \"Quiz Title\",
            \"description\": \"Brief description\",
            \"questions\": [
                {
                    \"id\": 1,
                    \"question\": \"Question text?\",
                    \"options\": {
                        \"A\": \"Option A text\",
                        \"B\": \"Option B text\",
                        \"C\": \"Option C text\",
                        \"D\": \"Option D text\"
                    },
                    \"correct_answer\": \"A\"
                }
            ]
        }
        Make the questions about general knowledge, science, or technology.";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Extract JSON from response (remove markdown code blocks if present)
                $text = preg_replace('/```json\s*|\s*```/', '', $text);
                $text = trim($text);
                
                $quizData = json_decode($text, true);
                
                if (json_last_error() === JSON_ERROR_NONE && isset($quizData['questions'])) {
                    return $quizData;
                }
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
        }

        return null;
    }

    public function submitQuiz(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'quiz_data' => 'required|json'
        ]);

        $quizData = json_decode($request->quiz_data, true);
        $userAnswers = $request->answers;

        // Calculate score using Gemini
        $score = $this->calculateScoreWithGemini($quizData, $userAnswers);

        if ($score !== null) {
            // Save score to database
            ScoreLogs::create([
                'user_id' => Auth::id(),
                'score' => $score,
                'quiz_data' => json_encode($quizData),
                'user_answers' => json_encode($userAnswers)
            ]);

            return response()->json([
                'success' => true,
                'score' => $score,
                'total' => count($quizData['questions']),
                'message' => "You scored {$score} out of " . count($quizData['questions'])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to calculate score'
        ], 500);
    }

    private function calculateScoreWithGemini($quizData, $userAnswers)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            return null;
        }

        $prompt = "Given this quiz data and user answers, calculate the score. Return ONLY a number representing the total correct answers.

Quiz Questions and Correct Answers:
" . json_encode($quizData['questions'], JSON_PRETTY_PRINT) . "

User Answers:
" . json_encode($userAnswers, JSON_PRETTY_PRINT) . "

Return only the number of correct answers (e.g., 7 if user got 7 correct out of 10). No explanation, just the number.";

        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Extract number from response
                preg_match('/\d+/', $text, $matches);
                
                if (isset($matches[0])) {
                    return (int) $matches[0];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Gemini Score Calculation Error: ' . $e->getMessage());
        }

        return null;
    }

    public function regenerateQuiz()
    {
        // Delete existing quiz
        Storage::delete('quiz/current_quiz.json');
        
        return redirect()->route('quiz.index')->with('success', 'New quiz generated!');
    }

    public function results()
    {
        $scores = ScoreLogs::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Quiz.results', compact('scores'));
    }
}
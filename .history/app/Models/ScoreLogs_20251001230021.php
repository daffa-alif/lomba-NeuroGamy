<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\ScoreLog;

class QuizController extends Controller
{
    public function index()
    {
        // Load quiz questions from JSON
        $quizData = $this->loadQuizData();
        
        return view('Quiz.index', [
            'quizzes' => $quizData['questions'] ?? []
        ]);
    }

    public function Confirmation()
    {
        return view('Quiz.confirmation');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        // Load quiz data
        $quizData = $this->loadQuizData();
        $userAnswers = $request->input('answers');

        // Prepare data for Gemini evaluation
        $evaluationPrompt = $this->prepareEvaluationPrompt($quizData['questions'], $userAnswers);

        // Send to Gemini for scoring
        $score = $this->evaluateWithGemini($evaluationPrompt);

        // Save score to database
        $scoreLog = ScoreLog::create([
            'user_id' => Auth::id(),
            'score' => $score,
            'answers' => json_encode($userAnswers),
            'quiz_data' => json_encode($quizData['questions'])
        ]);

        return redirect()->route('quiz.result', ['id' => $scoreLog->id])
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result($id)
    {
        $scoreLog = ScoreLogs::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('Quiz.result', [
            'scoreLog' => $scoreLog
        ]);
    }

    private function loadQuizData()
    {
        $jsonPath = storage_path('app/quiz_data.json');
        
        if (!file_exists($jsonPath)) {
            // Create default quiz data if doesn't exist
            $defaultData = [
                'questions' => [
                    [
                        'id' => 1,
                        'question' => 'What is the capital of France?',
                        'options' => [
                            'a' => 'London',
                            'b' => 'Berlin',
                            'c' => 'Paris',
                            'd' => 'Madrid'
                        ],
                        'correct_answer' => 'c'
                    ],
                    [
                        'id' => 2,
                        'question' => 'Which programming language is known as the "language of the web"?',
                        'options' => [
                            'a' => 'Python',
                            'b' => 'JavaScript',
                            'c' => 'Java',
                            'd' => 'C++'
                        ],
                        'correct_answer' => 'b'
                    ]
                ]
            ];
            
            Storage::put('quiz_data.json', json_encode($defaultData, JSON_PRETTY_PRINT));
        }

        $content = Storage::get('quiz_data.json');
        return json_decode($content, true);
    }

    private function prepareEvaluationPrompt($questions, $userAnswers)
    {
        $prompt = "Evaluate the following quiz answers and return ONLY a numeric score (0-100).\n\n";
        
        foreach ($questions as $question) {
            $questionId = $question['id'];
            $userAnswer = $userAnswers[$questionId] ?? 'not answered';
            $correctAnswer = $question['correct_answer'];
            
            $prompt .= "Question {$questionId}: {$question['question']}\n";
            $prompt .= "Correct Answer: {$correctAnswer}\n";
            $prompt .= "User Answer: {$userAnswer}\n\n";
        }
        
        $prompt .= "Calculate the score as a percentage (0-100). Return ONLY the number, nothing else.";
        
        return $prompt;
    }

    private function evaluateWithGemini($prompt)
    {
        try {
            $apiKey = env('GEMINI_API_KEY');
            
            if (!$apiKey) {
                // Fallback to manual calculation if no API key
                return $this->calculateScoreManually();
            }

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
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '0';
                
                // Extract numeric value
                preg_match('/\d+/', $text, $matches);
                return (int) ($matches[0] ?? 0);
            }

            // Fallback to manual calculation
            return $this->calculateScoreManually();
            
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            return $this->calculateScoreManually();
        }
    }

    private function calculateScoreManually()
    {
        // Simple fallback calculation
        $quizData = $this->loadQuizData();
        $questions = $quizData['questions'];
        $userAnswers = request()->input('answers', []);
        
        $correct = 0;
        $total = count($questions);

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $userAnswer = $userAnswers[$questionId] ?? '';
            
            if ($userAnswer === $question['correct_answer']) {
                $correct++;
            }
        }

        return $total > 0 ? round(($correct / $total) * 100) : 0;
    }
}
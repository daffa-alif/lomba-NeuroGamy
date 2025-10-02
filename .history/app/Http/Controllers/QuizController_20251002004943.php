<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{ScoreLogs,Book};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
public function Confirmation(\App\Models\Book $book, $pages = 0)
{
    return view('Quiz.confirmation', [
        'book' => $book,
        'pagesRead' => $pages,
    ]);
}





public function index(Request $request)
{
    $bookId = $request->query('book_id'); // ✅ dari query
    if (!$bookId) {
        return back()->with('error', 'Book ID tidak ditemukan.');
    }

    $book = \App\Models\Book::findOrFail($bookId);

    // load atau generate quiz
    $quizPath = "quiz/{$bookId}_quiz.json";
    if (\Storage::exists($quizPath)) {
        $quizData = json_decode(\Storage::get($quizPath), true);
    } else {
        $quizData = $this->generateQuizWithGemini($book);
        if ($quizData) {
            \Storage::put($quizPath, json_encode($quizData));
        } else {
            return back()->with('error', 'Gagal membuat quiz.');
        }
    }

    return view('Quiz.index', [
        'quiz' => $quizData,
        'book' => $book,
    ]);
}




    private function generateQuizWithGemini()
    {
        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            Log::error('Gemini API key is not configured.');
            return null;
        }

        $prompt = "Generate a quiz with 3 multiple choice questions. Each question should have 4 options (A, B, C, D) with only one correct answer. Return the response in valid JSON format with this exact structure:
        {
            \"title\": \"General Knowledge Quiz\",
            \"description\": \"Test your knowledge with these 10 questions.\",
            \"questions\": [
                {
                    \"id\": 1,
                    \"question\": \"What is the capital of Indonesia?\",
                    \"options\": {
                        \"A\": \"Jakarta\",
                        \"B\": \"Bandung\",
                        \"C\": \"Surabaya\",
                        \"D\": \"Bali\"
                    },
                    \"correct_answer\": \"A\"
                }
            ]
        }
        Make the questions about general knowledge, science, or technology.";

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';

        try {
            $response = Http::withHeaders([
                'Content-Type'   => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->timeout(60)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed while generating quiz.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }
            
            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Extract JSON from response (remove markdown code blocks if present)
            $text = preg_replace('/```json\s*|\s*```/', '', $text);
            $text = trim($text);
            
            $quizData = json_decode($text, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($quizData['questions'])) {
                return $quizData;
            }

            Log::warning('Failed to decode JSON from Gemini response.', ['response_text' => $text]);

        } catch (\Exception $e) {
            Log::error('Exception during Gemini API call for quiz generation: ' . $e->getMessage());
        }

        return null;
    }
public function submit(Request $request)
{
    $bookId = $request->input('book_id');
    $book = \App\Models\Book::findOrFail($bookId);

    $quizPath = "quiz/{$bookId}_quiz.json";
    $quizData = json_decode(\Storage::get($quizPath), true);

    $answers = $request->input('answers', []);
    $score = 0;

    foreach ($quizData['questions'] as $index => $q) {
        if (isset($answers[$index]) && $answers[$index] == $q['answer']) {
            $score++;
        }
    }

    // simpan skor ke ScoreLogs
    \App\Models\ScoreLogs::updateOrCreate(
        ['books_id' => $bookId, 'user_id' => \Auth::id()],
        [
            'title' => $book->book_title,
            'pages' => $quizData['pages'] ?? 0,
            'score' => $score,
        ]
    );

    return redirect()->route('home')
        ->with('success', "Quiz selesai! Skor kamu: {$score}/" . count($quizData['questions']));
}


    private function calculateScoreWithGemini($quizData, $userAnswers)
    {
        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            Log::error('Gemini API key is not configured.');
            return null;
        }

        $prompt = "Given this quiz data and user answers, calculate the score. Return ONLY a number representing the total correct answers.

Quiz Questions and Correct Answers:
" . json_encode($quizData['questions'], JSON_PRETTY_PRINT) . "

User Answers:
" . json_encode($userAnswers, JSON_PRETTY_PRINT) . "

Return only the number of correct answers (e.g., 7 if user got 7 correct out of 10). No explanation, just the number.";

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent';

        try {
            $response = Http::withHeaders([
                'Content-Type'   => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->timeout(60)->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed while calculating score.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Extract number from response
            preg_match('/\d+/', $text, $matches);
            
            if (isset($matches[0])) {
                return (int) $matches[0];
            }

            Log::warning('Could not find a number in Gemini score response.', ['response_text' => $text]);

        } catch (\Exception $e) {
            Log::error('Exception during Gemini API call for score calculation: ' . $e->getMessage());
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
        // Eager load the 'book' relationship for efficiency
        $scores = ScoreLogs::with('book')->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Quiz.results', compact('scores'));
    }
}


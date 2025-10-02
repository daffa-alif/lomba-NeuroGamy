<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\ScoreLogs;

class QuizController extends Controller
{
    // Halaman konfirmasi sebelum masuk quiz
   public function Confirmation($book, $pages = null)
{
    $bookModel = Book::findOrFail($book);

    // Buat log awal (jika belum ada) untuk user & book ini
    $log = ScoreLogs::create([
        'books_id' => $bookModel->id,
        'title'    => "Quiz untuk {$bookModel->book_title}" . ($pages ? " (halaman $pages)" : ""),
        'user_id'  => Auth::id(),
        'score'    => 0,  // default 0 dulu
        'pages'    => $pages ?? 0,
    ]);

    return view('Quiz.confirmation', [
        'book' => $bookModel,
        'pages' => $pages,
        'scorelog_id' => $log->id, // kirim ke view
    ]);
}


    // Halaman quiz
 public function index($book_id, $scorelog_id)
{
    $book = Book::findOrFail($book_id);

    // ambil quiz dari service / generator
    $quiz = ... ;

    return view('Quiz.index', [
        'book' => $book,
        'quiz' => $quiz,
        'scorelog_id' => $scorelog_id, // ✅ lempar ke blade
    ]);
}



    // Submit jawaban
public function submit(Request $request)
{
    $quizData = json_decode($request->input('quiz_data'), true);
    $answers = $request->input('answers', []);
    $score = 0;

    if (isset($quizData['questions'])) {
        foreach ($quizData['questions'] as $question) {
            $id = $question['id'];
            if (isset($answers[$id]) && $answers[$id] == $question['correct_answer']) {
                $score++;
            }
        }
    }

    // Ambil ID ScoreLogs dari request
    $scoreLogId = $request->input('scorelog_id');

    // Update skor di log yang sudah ada
    $log = ScoreLogs::find($scoreLogId);
    if ($log) {
        $log->update([
            'score' => $score
        ]);
    }

    return view('quiz.end', [
        'score' => $score,
        'title' => $quizData['title'] ?? 'Quiz'
    ]);
}




    // Dummy generator (bisa diganti API Gemini)
 private function generateQuizWithGemini(Book $book = null, $pages = null)
{
    $title = $book->book_title ?? 'Buku';
    $pageInfo = $pages ? "halaman $pages" : "beberapa halaman";

    $apiKey = config('services.gemini.api_key');
    if (empty($apiKey)) {
        \Log::error("Gemini API key belum diset.");
        return null;
    }

    // Prompt ke Gemini (gunakan heredoc biar rapi)
    $prompt = <<<EOT
Buatkan 3 soal pilihan ganda (4 opsi A-D) berdasarkan buku berjudul "$title", khusus dari $pageInfo.
Format jawaban HARUS JSON valid dengan struktur:

{
  "title": "Quiz untuk $title ($pageInfo)",
  "description": "Latihan soal berdasarkan bacaan dari buku $title.",
  "questions": [
    {
      "id": 1,
      "question": "...?",
      "options": {"A":"...","B":"...","C":"...","D":"..."},
      "correct_answer": "A"
    }
  ]
}

Jangan ada teks tambahan, hanya JSON valid saja.
EOT;

    $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post($endpoint, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            \Log::error("Gemini API gagal: " . $response->body());
            return null;
        }

        $json = $response->json();

        // Ambil output teks
        $output = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$output) {
            \Log::error("Tidak ada output dari Gemini: " . json_encode($json));
            return null;
        }

        // Bersihkan kode block ```json ... ```
        $output = preg_replace('/^```json|```$/m', '', trim($output));

        // Decode JSON
        $quizData = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error("JSON decode error: " . json_last_error_msg() . " | Output: " . $output);
            return null;
        }

        return $quizData;

    } catch (\Throwable $e) {
        \Log::error('Error Gemini Quiz: ' . $e->getMessage());
        return null;
    }
}



}

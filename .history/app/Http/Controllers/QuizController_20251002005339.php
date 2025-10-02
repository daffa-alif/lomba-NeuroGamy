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

        return view('Quiz.confirmation', [
            'book' => $bookModel,
            'pages' => $pages,
        ]);
    }

    // Halaman quiz
    public function index(Request $request)
    {
        $bookId = $request->query('book_id');
        if (!$bookId) {
            return back()->with('error', 'Book ID tidak ditemukan pada URL. Pastikan link mengandung ?book_id=...');
        }

        $book = Book::find($bookId);
        if (!$book) {
            return back()->with('error', 'Buku tidak ditemukan.');
        }

        $quizPath = "quiz/{$bookId}_quiz.json";
        $quizData = null;

        try {
            if (Storage::exists($quizPath)) {
                $quizData = json_decode(Storage::get($quizPath), true);
            } else {
                $quizData = $this->generateQuizWithGemini($book);
                if ($quizData && is_array($quizData)) {
                    Storage::put($quizPath, json_encode($quizData));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error saat membaca/menyimpan quiz: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat kuis.');
        }

        if (!is_array($quizData) || empty($quizData['questions'])) {
            return back()->with('error', 'Quiz tidak tersedia atau formatnya salah. Silakan generate ulang.');
        }

        return view('Quiz.index', [
            'quiz' => $quizData,
            'book' => $book,
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

        // Simpan ke ScoreLogs
        $log = ScoreLogs::create([
            'books_id' => $request->input('books_id'),
            'title'    => $quizData['title'] ?? 'Quiz',
            'user_id'  => Auth::id(),
            'score'    => $score,
            'pages'    => 0,
        ]);

        return redirect()->route('library.index')->with('success', "Quiz selesai! Skor kamu: $score");
    }

    // Dummy generator (bisa diganti API Gemini)
    private function generateQuizWithGemini(Book $book = null)
    {
        return [
            'title' => 'Sample Quiz untuk ' . ($book->book_title ?? 'Buku'),
            'description' => 'Latihan soal sederhana.',
            'questions' => [
                [
                    'id' => 1,
                    'question' => 'Ibu kota Indonesia?',
                    'options' => ['A' => 'Jakarta', 'B' => 'Bandung', 'C' => 'Surabaya', 'D' => 'Bali'],
                    'correct_answer' => 'A',
                ],
                [
                    'id' => 2,
                    'question' => 'H2O adalah molekul apa?',
                    'options' => ['A'=>'Karbon','B'=>'Air','C'=>'Oksigen','D'=>'Nitrogen'],
                    'correct_answer' => 'B',
                ],
                [
                    'id' => 3,
                    'question' => 'Bahasa pemrograman server-side populer?',
                    'options' => ['A'=>'HTML','B'=>'CSS','C'=>'PHP','D'=>'SVG'],
                    'correct_answer' => 'C',
                ],
            ],
        ];
    }
}

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

    // Ambil log terakhir untuk user & book
    $log = \App\Models\ScoreLogs::where('books_id', $bookId)
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->latest()
        ->with('book')
        ->first();

    if (!$log) {
        return back()->with('error', 'Log bacaan tidak ditemukan. Silakan baca buku dulu.');
    }

    $book = $log->book;
    $pages = $log->pages;

    $quizPath = "quiz/{$bookId}_{$pages}.json";
    $quizData = null;

    if (\Illuminate\Support\Facades\Storage::exists($quizPath)) {
        $quizData = json_decode(\Illuminate\Support\Facades\Storage::get($quizPath), true);
    } else {
        // 🔥 generate quiz sesuai buku + halaman yang dibaca
        $quizData = $this->generateQuizWithGemini($book, $pages);

        if ($quizData && is_array($quizData)) {
            \Illuminate\Support\Facades\Storage::put($quizPath, json_encode($quizData));
        }
    }

    if (!is_array($quizData) || empty($quizData['questions'])) {
        return back()->with('error', 'Quiz gagal dibuat.');
    }

    return view('Quiz.index', [
        'quiz' => $quizData,
        'book' => $book,
        'pages' => $pages,
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

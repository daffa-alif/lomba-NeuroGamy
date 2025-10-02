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
        'scorelog_id' => $log->id, // ✅ lempar ke view
    ]);
}



   // Submit jawaban
public function submit(Request $request)
{
    $answers = $request->input('answers', []);
    $quizData = json_decode($request->input('quiz_data'), true);

    $score = 0;
    if ($quizData && isset($quizData['questions'])) {
        foreach ($quizData['questions'] as $question) {
            $id = $question['id'];
            if (isset($answers[$id]) && $answers[$id] == $question['correct_answer']) {
                $score++;
            }
        }
    }

    // Ambil ID ScoreLogs dari request
    $scoreLogId = $request->input('scorelog_id');

    // Update skor di log yang sudah ada (tanpa update title lagi)
    $log = ScoreLogs::find($scoreLogId);
    if ($log) {
        $log->update([
            'score' => $score
        ]);
    }

    // Ambil judul buku dari relasi log (lebih natural daripada quiz JSON)
    $bookTitle = $log && $log->book ? $log->book->book_title : 'Buku';

    return view('quiz.end', [
        'score' => $score,
        'title' => "Hasil Quiz: {$bookTitle}" // ✅ tidak lagi pakai "Quiz untuk $title (halaman ...)"
    ]);
}




}

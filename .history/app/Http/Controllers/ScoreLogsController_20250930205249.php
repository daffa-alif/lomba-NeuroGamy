<?php

namespace App\Http\Controllers;

use App\Models\ScoreLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'books_id' => 'required|exists:books,id',
        'pages'    => 'required|integer|min:1',
    ]);

    $book = \App\Models\Book::findOrFail($validated['books_id']);

    $log = \App\Models\ScoreLogs::create([
        'books_id' => $book->id,
        'title'    => $book->book_title,
        'user_id'  => \Illuminate\Support\Facades\Auth::id(),
        'score'    => null,
        'pages'    => $validated['pages'],
    ]);

    // ✅ Redirect to confirmation page after success
    return redirect()
        ->route('confirmation', ['id' => $log->id])
        ->with('success', 'Log berhasil dibuat. Silakan lanjut ke kuis.');
}



}

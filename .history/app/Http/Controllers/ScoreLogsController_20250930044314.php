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
    ]);

    $book = \App\Models\Book::findOrFail($validated['books_id']);

    $log = ScoreLogs::create([
        'books_id' => $book->id,
        'title'    => $book->book_title, // ✅ ambil dari tabel books
        'user_id'  => Auth::id(),
        'score'    => null,
        'score'    => null,
    ]);

    return response()->json([
        'success' => true,
        'data'    => $log
    ]);
}


}

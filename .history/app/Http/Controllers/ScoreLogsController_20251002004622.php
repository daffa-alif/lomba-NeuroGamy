<?php

namespace App\Http\Controllers;

use App\Models\{ScoreLogs};
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

        $book = Book::findOrFail($validated['books_id']);

        $log = ScoreLogs::create([
            'books_id' => $book->id,
            'title'    => $book->book_title,
            'user_id'  => Auth::id(),
            'score'    => null,
            'pages'    => $validated['pages'],
        ]);

        // ✅ Redirect ke confirmation dengan parameter yg benar
        return redirect()
            ->route('Confirmation', [
                'book'  => $book->id,
                'pages' => $validated['pages'],
            ])
            ->with('success', 'Log berhasil dibuat. Silakan lanjut ke kuis.');
    }



}

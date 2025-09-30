<?php

namespace App\Http\Controllers;

use App\Models\ScoreLogs;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $book = Book::findOrFail($request->book_id);

            $log = ScoreLog::create([
                'user_id'  => Auth::id(),
                'books_id' => $book->id,
                'title'    => $book->title,
                'score'    => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log berhasil disimpan',
                'data'    => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}

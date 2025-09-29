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
            'title'    => 'required|string|max:255',
        ]);

        $exists = ScoreLogs::where('books_id', $validated['books_id'])
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Log sudah ada.'
            ], 409);
        }

        $log = ScoreLogs::create([
            'books_id' => $validated['books_id'],
            'title'    => $validated['title'],
            'user_id'  => Auth::id(),
            'score'    => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log berhasil disimpan.',
            'data'    => $log
        ]);
    }
}

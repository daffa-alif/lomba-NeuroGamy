<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreLog;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'books_id' => 'required|exists:books,id',
                'title'    => 'required|string',
            ]);

            // cek apakah sudah ada log user untuk buku ini
            $exists = ScoreLog::where('user_id', Auth::id())
                ->where('books_id', $request->books_id)
                ->first();

            if ($exists) {
                return response()->json([
                    'message' => 'Log sudah ada'
                ], 409);
            }

            $log = ScoreLog::create([
                'user_id'  => Auth::id(),
                'books_id' => $request->books_id,
                'title'    => $request->title,
                'score'    => null, // default kosong
            ]);

            return response()->json([
                'message' => 'Log berhasil dibuat',
                'data'    => $log,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}

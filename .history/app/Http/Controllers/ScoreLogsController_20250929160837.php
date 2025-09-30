<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreLog;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // pastikan user login
    }

    /**
     * Store a newly created ScoreLog.
     * Request body must include: books_id (int). title optional.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'books_id' => 'required|integer|exists:books,id',
            'title'    => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        // cegah duplikat: satu user hanya boleh 1 log per buku (ubah sesuai kebutuhan)
        $already = ScoreLog::where('user_id', $userId)
                           ->where('books_id', $data['books_id'])
                           ->first();

        if ($already) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah pernah submit log untuk buku ini.'
            ], 409);
        }

        // jika title tidak dikirim, ambil dari tabel books
        if (empty($data['title'])) {
            $book = Book::find($data['books_id']);
            $data['title'] = $book ? $book->title : null;
        }

        $log = ScoreLog::create([
            'user_id'  => $userId,
            'books_id' => $data['books_id'],
            'title'    => $data['title'],
            'score'    => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log berhasil dibuat.',
            'data'    => $log
        ], 201);
    }
}

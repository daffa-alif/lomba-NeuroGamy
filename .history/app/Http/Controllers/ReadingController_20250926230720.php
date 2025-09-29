<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http;

class ReadingController extends Controller
{
    public function index($id)
    {
        // Ambil data buku
        $book = Book::findOrFail($id);

        return view('Reading.Index', compact('book'));
    }

  public function summarize(Request $request, Book $book)
{
    $page = $request->input('page');

    // sementara coba return dummy biar cek dulu
    return response()->json([
        'output' => "Ini ringkasan dummy untuk halaman {$page} dari buku {$book->book_title}"
    ]);
}

}

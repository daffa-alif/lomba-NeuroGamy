<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookClassification;
use Illuminate\Http\Request;

class BookController extends Controller
{
   public function index()
    {
        $books = Book::with('classification')->get();
        $classifications = BookClassification::all();
        return view('Admin.books.index', compact('books', 'classifications'));
    }

    public function store(Request $request)
    {
        // Kalau classification_id tidak dikirim tapi classification_name ada → buat baru
        if (!$request->classification_id && $request->classification_name) {
            $classification = BookClassification::create([
                'classification' => $request->classification_name,
            ]);
            $request->merge(['classification_id' => $classification->id]);
        }

        $book = Book::create([
            'classification_id' => $request->classification_id,
            'book_title'        => $request->book_title,
            'file_name'         => $request->file_name,
            'book_description'  => $request->book_description,
        ]);

        return response()->json(['success' => true, 'data' => $book->load('classification')]);
    }

    public function update(Request $request, Book $book)
    {
        if (!$request->classification_id && $request->classification_name) {
            $classification = BookClassification::create([
                'classification' => $request->classification_name,
            ]);
            $request->merge(['classification_id' => $classification->id]);
        }

        $book->update([
            'classification_id' => $request->classification_id,
            'book_title'        => $request->book_title,
            'file_name'         => $request->file_name,
            'book_description'  => $request->book_description,
        ]);

        return response()->json(['success' => true, 'data' => $book->load('classification')]);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['success' => true]);
    }
}

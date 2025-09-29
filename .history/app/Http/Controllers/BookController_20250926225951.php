<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('classification')->latest()->get();
        $classifications = BookClassification::all();
        return view('admin.books.index', compact('books', 'classifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classification_id' => 'required|exists:book_classifications,id',
            'book_title'        => 'required|string|max:255',
            'file_name'         => 'nullable|file|mimes:pdf|max:2048',
            'book_description'  => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file_name')) {
            // simpan ke storage/app/public/books
            $filePath = $request->file('file_name')->store('books', 'public');
        }

        $book = Book::create([
            'classification_id' => $request->classification_id,
            'book_title'        => $request->book_title,
            'file_name'         => $filePath, // simpan path relatif, ex: "books/xxxx.pdf"
            'book_description'  => $request->book_description,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $book->load('classification'),
        ]);
    }

    public function edit(Book $book)
    {
        $classifications = BookClassification::all();
        return view('admin.books.edit', compact('book', 'classifications'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'classification_id' => 'required|exists:book_classifications,id',
            'book_title'        => 'required|string|max:255',
            'file_name'         => 'nullable|file|mimes:pdf|max:2048',
            'book_description'  => 'nullable|string',
        ]);

        $filePath = $book->file_name;
        if ($request->hasFile('file_name')) {
            // simpan ke storage/app/public/books
            $filePath = $request->file('file_name')->store('books', 'public');
        }

        $book->update([
            'classification_id' => $request->classification_id,
            'book_title'        => $request->book_title,
            'file_name'         => $filePath,
            'book_description'  => $request->book_description,
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['success' => true]);
    }
}

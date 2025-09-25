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
        $data = $request->validate([
            'classification_id' => 'required|integer',
            'book_title'        => 'required|string|max:255',
            'file_name'         => 'required|string|max:255',
            'book_description'  => 'nullable|string',
        ]);

        $book = Book::create($data);

        return response()->json($book->load('classification'));
    }

    public function show($id)
    {
        return response()->json(Book::with('classification')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $data = $request->validate([
            'classification_id' => 'required|integer',
            'book_title'        => 'required|string|max:255',
            'file_name'         => 'required|string|max:255',
            'book_description'  => 'nullable|string',
        ]);

        $book->update($data);

        return response()->json($book->load('classification'));
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return response()->json(['success' => true]);
    }
}

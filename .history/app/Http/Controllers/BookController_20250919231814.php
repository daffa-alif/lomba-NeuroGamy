<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'classification_id' => 'required|integer',
            'book_title'        => 'required|string|max:255',
            'file_name'         => 'required|string|max:255',
            'book_description'  => 'nullable|string',
        ]);

        $book = Book::create($request->all());

        return response()->json(['success' => true, 'book' => $book]);
    }

    public function show($id)
    {
        $book = Book::with('classification')->findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json(['success' => true, 'book' => $book]);
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return response()->json(['success' => true]);
    }
}

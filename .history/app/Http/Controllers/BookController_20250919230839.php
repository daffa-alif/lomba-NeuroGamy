<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Controllers\Controller;
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
        $book = Book::create($request->all());
        return response()->json($book);
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }

    public function update(Request $request, Book $book)
    {
        $book->update($request->all());
        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['success' => true]);
    }
}

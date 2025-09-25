<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookClassification;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Show books index page
     */
    public function index(Request $request)
    {
        // If request is AJAX, return JSON list
        if ($request->ajax()) {
            $books = Book::with('classification')->latest()->get();
            return response()->json($books);
        }

        // Otherwise return the Blade view
        return view('Admin.books.index');
    }

    /**
     * Store a new book
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'book_title'       => 'required|string|max:255',
            'file_name'        => 'required|string|max:255',
            'book_description' => 'nullable|string',
            'classification'   => 'required|string|max:255',
        ]);

        // find or create classification
        $classification = BookClassification::firstOrCreate([
            'classification' => $data['classification']
        ]);

        $book = Book::create([
            'classification_id' => $classification->id,
            'book_title'        => $data['book_title'],
            'file_name'         => $data['file_name'],
            'book_description'  => $data['book_description'],
        ]);

        return response()->json(['success' => true, 'book' => $book->load('classification')]);
    }

    /**
     * Show single book
     */
    public function show($id)
    {
        $book = Book::with('classification')->findOrFail($id);
        return response()->json($book);
    }

    /**
     * Update book
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'book_title'       => 'required|string|max:255',
            'file_name'        => 'required|string|max:255',
            'book_description' => 'nullable|string',
            'classification'   => 'required|string|max:255',
        ]);

        $book = Book::findOrFail($id);

        // find or create classification
        $classification = BookClassification::firstOrCreate([
            'classification' => $data['classification']
        ]);

        $book->update([
            'classification_id' => $classification->id,
            'book_title'        => $data['book_title'],
            'file_name'         => $data['file_name'],
            'book_description'  => $data['book_description'],
        ]);

        return response()->json(['success' => true, 'book' => $book->load('classification')]);
    }

    /**
     * Delete book
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['success' => true]);
    }
}

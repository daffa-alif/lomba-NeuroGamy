<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookClassification;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Display the library page with books and category filter
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get all classifications for the filter dropdown
        $classifications = BookClassification::orderBy('classification', 'asc')->get();
        
        // Start building the books query
        $query = Book::with('classification');
        
        // Apply category filter if provided
        $selectedCategory = $request->get('category');
        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->where('classification_id', $selectedCategory);
        }
        
        // Apply search filter if provided
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('book_title', 'LIKE', '%' . $search . '%')
                  ->orWhere('book_description', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Order books by title
        $books = $query->orderBy('book_title', 'asc')->paginate(12);
        
        // Keep query parameters in pagination links
        $books->appends($request->query());
        
        return view('library', compact('books', 'classifications', 'selectedCategory', 'search'));
    }
    
    /**
     * Show individual book details
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $book = Book::with('classification')->findOrFail($id);
        
        // Get related books from the same category (excluding current book)
        $relatedBooks = Book::with('classification')
            ->where('classification_id', $book->classification_id)
            ->where('id', '!=', $book->id)
            ->limit(4)
            ->get();
        
        return view('book-detail', compact('book', 'relatedBooks'));
    }
    
    /**
     * Download book file
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $book = Book::findOrFail($id);
        
        $filePath = storage_path('app/public/books/' . $book->file_name);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath, $book->book_title . '.' . pathinfo($book->file_name, PATHINFO_EXTENSION));
    }
    
    /**
     * Get books by category (AJAX endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooksByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        $search = $request->get('search', '');
        
        $query = Book::with('classification');
        
        if ($categoryId && $categoryId !== 'all') {
            $query->where('classification_id', $categoryId);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('book_title', 'LIKE', '%' . $search . '%')
                  ->orWhere('book_description', 'LIKE', '%' . $search . '%');
            });
        }
        
        $books = $query->orderBy('book_title', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'books' => $books,
            'count' => $books->count()
        ]);
    }
    
    /**
     * Get all categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = BookClassification::withCount('books')
            ->orderBy('classification', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}
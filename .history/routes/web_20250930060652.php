<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AiController, AuthController, BookController, UserController, BookClassificationController, LibraryController, ReadingController, ScoreLogsController, Quiz;
use App\Models\Book;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/library/book/{id}', [LibraryController::class, 'show'])->name('library.show');
Route::get('/library/download/{id}', [LibraryController::class, 'download'])->name('library.download');

// AJAX routes
Route::get('/api/books-by-category', [LibraryController::class, 'getBooksByCategory']);
Route::get('/api/categories', [LibraryController::class, 'getCategories']);

Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'viewRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/ai/generate', [AiController::class, 'generate'])->name('ai.generate');

Route::middleware(['auth'])->group(function () {
    Route::get('/chatbot', function () {
        return view('Summary.index'); // contoh view chatbot
    })->name('chatbot');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('admin.dashboard');

 Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
        
  Route::get('/dashboard', fn() => view('Admin.dashboard'))->name('admin.dashboard');

  Route::get('/users', [UserController::class, 'index'])->name('users.index');
  
  Route::resource('classifications', BookClassificationController::class);
  Route::resource('books', \App\Http\Controllers\BookController::class);

});

Route::get('/books/file/{filename}', function ($filename) {
    $path = storage_path('app/private/public/books/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('books.file')->middleware(['auth', 'admin']);


Route::get('/reading/file/{id}', function ($id) {
    $book = App\Models\Book::findOrFail($id);

    $path = $book->file_name; // contoh: "books/1758301447_1000133087.pdf"

    if (!Storage::disk('private')->exists($path)) {
        abort(404, "File not found: {$path}");
    }

    return response()->file(Storage::disk('private')->path($path));
})->name('reading.file');


Route::middleware(['auth'])->group(function () {
    Route::get('/reading/file/{id}', function ($id) {
        $book = Book::findOrFail($id);

        $path = $book->file_name;

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('private')->path($path));
    })->name('reading.file');
    Route::get('/reading/{book}', [ReadingController::class, 'index'])->name('reading.index');
    Route::post('/reading/{book}/summarize', [ReadingController::class, 'summarize'])->name('reading.summarize');
    Route::post('/scorelogs', [ScoreLogsController::class, 'store'])->name('scorelogs.store');

    Route::get('/quiz/confirmation', [QuizController::class, 'Confirmation'])->name('Confirmation');
});


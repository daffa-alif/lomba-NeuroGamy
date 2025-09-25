<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AiController, AuthController, BookController, UserController, BookClassificationController, LibraryController};

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/library/book/{id}', [LibraryController::class, 'show'])->name('library.show');
Route::get('/library/download/{id}', [LibraryController::class, 'download'])->name('library.download');

// AJAX routes
Route::get('/api/books-by-category', [LibraryController::class, 'getBooksByCategory']);
Route::get('/api/categories', [LibraryController::class, 'getCategories']);
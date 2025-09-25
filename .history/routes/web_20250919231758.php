<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AiController, AuthController, BookController, UserController};

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
        return view('chatbot'); // contoh view chatbot
    })->name('chatbot');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('admin.dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

 Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // ✅ CRUD AJAX for books
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

    // ✅ Classification CRUD (for select/create)
    Route::post('/classifications', [BookClassificationController::class, 'store'])->name('classifications.store');
});
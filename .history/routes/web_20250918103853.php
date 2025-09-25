<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get

Route::get('/chatbot', [AiController::class, 'chatbot']);
Route::post('/ai/generate', [AiController::class, 'generate'])->name('ai.generate');
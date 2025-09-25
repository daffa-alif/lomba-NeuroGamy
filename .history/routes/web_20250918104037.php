<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class, 'viewLogin']);
Route::get('/register',[AuthController::class, 'ViewRegister']);

Route::get('/chatbot', [AiController::class, 'chatbot']);
Route::post('/ai/generate', [AiController::class, 'generate'])->name('ai.generate');
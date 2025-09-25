<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AiController;

Route::post('/ai/generate', [AiController::class, 'generate']);

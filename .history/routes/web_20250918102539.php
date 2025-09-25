<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/ai/generate', [AiController::class, 'generate']);

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::get('/q', [QuestionController::class, 'test']);

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [QuestionController::class, 'store']);
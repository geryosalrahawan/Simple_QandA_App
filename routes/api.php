<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAnswerController;

Route::get('/q', [QuestionController::class, 'test']);

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [QuestionController::class, 'store']);

Route::post('/answers/submit', [UserAnswerController::class, 'submit']);
Route::get('/answers', [UserAnswerController::class, 'index']);
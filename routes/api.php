<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\QuizController;


Route::get('/quizzes', [QuizController::class, 'index']);
Route::post('/quizzes', [QuizController::class, 'store']);
Route::get('/quizzes/{id}', [QuizController::class, 'show']);

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [QuestionController::class, 'store']);

Route::get('/quizzes/{quizId}/questions', [QuestionController::class, 'index']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'storeForQuiz']);


Route::post('/answers/submit', [UserAnswerController::class, 'submit']);
Route::get('/answers', [UserAnswerController::class, 'index']);
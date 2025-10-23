<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:api'])->group(function () {


    Route::post('/answers/submit', [UserAnswerController::class, 'submit']);
    Route::get('/answers', [UserAnswerController::class, 'index']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    
});


Route::middleware(['auth:api', 'admin'])->group(function () {

    Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/quizzes', [QuizController::class, 'index']);
Route::get('/quizzes/{quizId}/questions', [QuestionController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::post('/questions', [QuestionController::class, 'store']);

    Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'storeForQuiz']);


    Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);


});







Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
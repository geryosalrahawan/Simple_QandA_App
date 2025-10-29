<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizAttemptController;


Route::middleware(['auth:api'])->group(function () {

    Route::get('/user',[AuthController::class, 'me']);
    
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);//getting a specific quiz

    Route::post('/quizzes/{id}/submit', [QuizAttemptController::class, 'submit']);
    Route::get('/my-attempts', [QuizAttemptController::class, 'history']);
    Route::post('/quizzes/{id}/start', [QuizAttemptController::class, 'start']);

});


Route::middleware(['auth:api', 'admin'])->group(function () {
    
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/quizzes/{quiz}/questions', [QuestionController::class, 'index']);
    
    
    
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'storeForQuiz']);


    // Quizzes
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy']);

    // Questions
    Route::put('/questions/{question}', [QuestionController::class, 'update']);
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);

    Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);

    Route::get('/analytics', [QuizAttemptController::class, 'analytics']);




});






Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

});
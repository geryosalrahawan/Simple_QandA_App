<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::middleware(['auth:api'])->group(function () {

    Route::get('/user',[AuthController::class, 'me']);
    
    Route::post('/answers/submit', [UserAnswerController::class, 'submit']);//subimtting ansewer
    Route::get('/answers', [UserAnswerController::class, 'index']);//show the user answers
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);//getting a specific quiz
    
});
Route::get('/yes', [UserController::class, 'test']);

Route::middleware(['auth:api', 'admin'])->group(function () {

    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{quizId}/questions', [QuestionController::class, 'index']);

    Route::post('/quizzes', [QuizController::class, 'store']);
    
    // Route::post('/questions', [QuestionController::class, 'store']);

    Route::post('/quizzes/{quizId}/questions', [QuestionController::class, 'storeForQuiz']);


    Route::put('/questions/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

    Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);

});







Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
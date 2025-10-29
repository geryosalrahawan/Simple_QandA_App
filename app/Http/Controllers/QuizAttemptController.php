<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    // --- Submit a quiz ---
public function submit(Request $request, $quizId)
{
    $quiz = Quiz::findOrFail($quizId);

    $attempt = QuizAttempt::where('user_id', auth()->id())
        ->where('quiz_id', $quizId)
        ->latest()
        ->first();

    if (!$attempt) {
        return response()->json(['error' => 'Quiz attempt not found.'], 404);
    }

    $elapsed = now()->diffInSeconds($attempt->started_at);

    if ($elapsed > $quiz->duration) {
        return response()->json([
            'error' => '⏰ Time is up! You cannot submit this quiz anymore.'
        ], 403);
    }

    $answers = $request->input('answers', []);
    $score = $this->calculateScore($quiz, $answers);

    $attempt->update([
        'submitted_at' => now(),
        'score' => $score
    ]);

    return response()->json([
        'message' => 'Quiz submitted successfully',
        'score' => $score,
        'total' => $quiz->questions()->count(),
        'time_taken' => $elapsed
    ]);
}


private function calculateScore($quiz, $answers)
{
    $score = 0;

    foreach ($quiz->questions as $question) {
        $correctOption = $question->options()->where('is_correct', true)->first();

        if (isset($answers[$question->id]) && $answers[$question->id] == $correctOption->id) {
            $score++;
        }
    }

    return $score;
}

    // --- Get current user's quiz history ---
    public function history()
    {
        $attempts = QuizAttempt::with('quiz')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($attempts);
    }

    // --- Admin analytics ---
    public function analytics()
    {
        $this->authorize('viewAny', QuizAttempt::class); // only admin

        $quizzes = Quiz::with('attempts')->get();

        $data = $quizzes->map(function ($quiz) {
            $attempts = $quiz->attempts;
            $avg = $attempts->count() ? $attempts->avg('score') : 0;
            return [
                'quiz_id' => $quiz->id,
                'title' => $quiz->title,
                'attempts' => $attempts->count(),
                'average_score' => $avg,
            ];
        });

        return response()->json($data);
    }


public function start($id)
{
    $quiz = Quiz::findOrFail($id);

    // create or find the active attempt
    $attempt = QuizAttempt::firstOrCreate(
        [
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
        ],
        [
            'started_at' => now(),
            'score' => 0,
            'answers' => json_encode([]),
        ]
    );

    return response()->json([
        'quiz' => $quiz->load('questions.options'),
        'duration' => $quiz->duration,  //  send duration here
        'attempt_id' => $attempt->id,
        'started_at' => $attempt->started_at,
    ]);
}


}
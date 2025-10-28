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
        $request->validate([
            'answers' => 'required|array',
        ]);

        $quiz = Quiz::with('questions.options')->findOrFail($quizId);

        $score = 0;
        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->firstWhere('is_correct', true);
            if (isset($request->answers[$question->id]) &&
                $request->answers[$question->id] == $correctOption->id) {
                $score++;
            }
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'answers' => $request->answers,
            'score' => $score,
        ]);

        return response()->json([
            'message' => 'Quiz submitted!',
            'score' => $score,
            'total' => $quiz->questions->count(),
            'attempt' => $attempt
        ]);
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
}
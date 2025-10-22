<?php

namespace App\Http\Controllers;

use App\Models\UserAnswer;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;

class UserAnswerController extends Controller
{
    // Submit answers
    public function submit(Request $request)
    {
        $data = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.option_id' => 'required|exists:options,id',
            'user_id' => 'nullable|integer'
        ]);

        $score = 0;
        $total = count($data['answers']);
        $userAnswers = [];

        foreach ($data['answers'] as $answer) {
            $option = Option::find($answer['option_id']);

            // Check if correct
            $isCorrect = $option->is_correct ? 1 : 0;
            $score += $isCorrect;
            
            // Check if already answered
            $exists = UserAnswer::where('user_id', $data['user_id'])
                ->where('question_id', $answer['question_id'])
                ->exists();

            if ($exists) {
                continue; // skip if already answered
            }

            // Store user answer
            $userAnswers[] = UserAnswer::create([
                'question_id' => $answer['question_id'],
                'option_id' => $answer['option_id'],
                'user_id' => $data['user_id'] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Answers submitted successfully.',
            'score' => $score,
            'total_questions' => $total,
            'percentage' => round(($score / $total) * 100, 2),
        ]);
    }

    // View user answers (optional)
    public function index(Request $request)
    {
        $userId = $request->query('user_id');
        $query = UserAnswer::with(['question', 'option']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->get();
    }
}
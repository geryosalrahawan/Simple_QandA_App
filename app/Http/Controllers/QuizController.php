<?php

namespace App\Http\Controllers;
use App\Http\Resources\QuizResource;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Get all quizzes
    public function index()
    {
        return Quiz::with('questions.options')->get();
    }

    // Create a new quiz
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.title' => 'required|string',
            'questions.*.description' => 'nullable|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
        ]);

        $quiz = Quiz::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        foreach ($data['questions'] as $qData) {
            $question = $quiz->questions()->create([
                'title' => $qData['title'],
                'description' => $qData['description'] ?? null,
            ]);

            foreach ($qData['options'] as $option) {
                $question->options()->create($option);
            }
        }

        return response()->json($quiz->load('questions.options'), 201);
    }

    // Show single quiz with questions
public function show($id)
{

    $quiz = Quiz::with('questions.options')->find($id);

    if (!$quiz) {
        return response()->json(['message' => 'Quiz not found'], 404);
    }

    return new QuizResource($quiz);
}
}
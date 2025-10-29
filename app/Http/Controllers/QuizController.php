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
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'questions' => 'required|array|min:1',
    //         'questions.*.title' => 'required|string',
    //         'questions.*.description' => 'nullable|string',
    //         'questions.*.options' => 'required|array|min:2',
    //         'questions.*.options.*.text' => 'required|string',
    //         'questions.*.options.*.is_correct' => 'required|boolean',
    //     ]);

    //     $quiz = Quiz::create([
    //         'title' => $data['title'],
    //         'description' => $data['description'] ?? null,
    //     ]);

    //     foreach ($data['questions'] as $qData) {
    //         $question = $quiz->questions()->create([
    //             'title' => $qData['title'],
    //             'description' => $qData['description'] ?? null,
    //         ]);

    //         foreach ($qData['options'] as $option) {
    //             $question->options()->create($option);
    //         }
    //     }

    //     return response()->json($quiz->load('questions.options'), 201);
    // }


    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration' => 'required|integer|min:30',
    ]);

    $quiz = Quiz::create($validated);

    // Only add questions if they exist in request
    if ($request->has('questions')) {
        foreach ($request->questions as $qData) {
            $question = $quiz->questions()->create([
                'title' => $qData['title'],
            ]);

            foreach ($qData['options'] as $optData) {
                $question->options()->create($optData);
            }
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


public function update(Request $request, $id)
{
    $quiz = Quiz::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration' => 'sometimes|integer|min:30',
    ]);

    $quiz->update($validated);

    return response()->json($quiz, 200);
}

public function destroy($id)
{
    $quiz = Quiz::findOrFail($id);
    $quiz->delete();

    return response()->json(['message' => 'Quiz deleted successfully'], 200);
}


}
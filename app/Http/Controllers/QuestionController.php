<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuestionController extends Controller
{
        public function test()
    {
        return "hello";
    }

 // Get all questions for a quiz
    public function index($quizId)
    {
        return Question::with('options')
            ->where('quiz_id', $quizId)
            ->get();
    }
public function store(Request $request)
{
    $data = $request->validate([
        'quiz_id' => 'required|integer|exists:quizzes,id',
        'title' => 'required|string',
        'description' => 'nullable|string',
        'options' => 'required|array|min:2',
        'options.*.text' => 'required|string',
        'options.*.is_correct' => 'required|boolean',
    ]);
$quiz = Quiz::findOrFail($data['quiz_id']);
$question = $quiz->questions()->create([
    'title' => $data['title'],
    'description' => $data['description'] ?? null,
]);

    foreach ($data['options'] as $option) {
        $question->options()->create($option);
    }

    return response()->json($question->load('options'), 201);
}

public function storeForQuiz(Request $request, $quizId)
{
    $data = $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'options' => 'required|array|min:2',
        'options.*.text' => 'required|string',
        'options.*.is_correct' => 'required|boolean',
    ]);

    // Ensure the quiz exists
    $quiz = Quiz::findOrFail($quizId);

    // Create the question for this quiz
    $question = $quiz->questions()->create([
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
    ]);

    // Create the options
    foreach ($data['options'] as $option) {
        $question->options()->create($option);
    }

    return response()->json($question->load('options'), 201);
}

   

    // Update a question
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string'
        ]);

        $question->update($data);
        return response()->json($question->load('options'));
    }

    // Delete a question
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully']);
    }
}
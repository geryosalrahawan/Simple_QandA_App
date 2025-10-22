<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
        public function test()
    {
        return "hello";
    }
    public function index()
    {
        return Question::with('options')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        foreach ($data['options'] as $option) {
            $question->options()->create($option);
        }

        return response()->json($question->load('options'), 201);
    }
}
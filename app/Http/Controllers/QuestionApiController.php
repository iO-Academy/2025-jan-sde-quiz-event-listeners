<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionApiController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'question' => 'required|string|min:5|max:70',
            'hint' => 'nullable|string|min:5|max:70',
            'points' => 'required|integer',
            'quiz_id' => 'required|integer'
        ]);

        $newQuestion = new Question;

        $newQuestion->question = $request->question;
        $newQuestion->hint = $request->hint;
        $newQuestion->points = $request->points;
        $newQuestion->quiz_id = $request->quiz_id;

        $newQuestion->save();

        return response()->json([
            'message' => 'Question Created',
        ],201);
    }
}

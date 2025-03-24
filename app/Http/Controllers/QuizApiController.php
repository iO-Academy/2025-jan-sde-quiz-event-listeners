<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class QuizApiController extends Controller
{
    public function all()
    {
        $quizzes = Quiz::all();

        return response()->json([
            'message' => 'Quizzes retrieved',
            'data' => $quizzes,
        ], 200);
    }
}

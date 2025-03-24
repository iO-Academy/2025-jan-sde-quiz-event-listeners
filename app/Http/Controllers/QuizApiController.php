<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;

class QuizApiController extends Controller
{
    public function all(): JsonResponse
    {
        $quizzes = Quiz::all();

        return response()->json([
            'message' => 'Quizzes retrieved',
            'data' => $quizzes,
        ], 200);
    }

    public function find(int $id): JsonResponse
    {
        $quiz = Quiz::with('questions')->find($id);

        return response()->json([
            'message' => 'Quiz retrieved',
            'data' => $quiz,
        ], 200);
    }
}

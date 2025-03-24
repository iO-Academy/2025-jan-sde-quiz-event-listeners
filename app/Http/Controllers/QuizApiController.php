<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizRequest;
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

    public function create(CreateQuizRequest $request): JsonResponse
    {
        $quiz = new Quiz();
        $quiz->name = $request->name;
        $quiz->description = $request->description;
        $quiz->save();

        return response()->json([
            'message' => 'Quiz Created'
        ], 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use http\Env\Request;
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

    public function create( $request): JsonResponse
    {


        return response()->json([
            'message' => 'Quiz Created'
        ], 201);
    }
}

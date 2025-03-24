<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizRequest;
use App\Http\Requests\EditQuizRequest;
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
        $quiz = Quiz::with('questions.answers')->find($id);
        if ($quiz == []) {
            return response()->json([
                'message' => 'Quiz not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Quiz retrieved',
            'data' => $quiz,
        ], 200);
    }

    public function create(CreateQuizRequest $request): JsonResponse
    {
        $quiz = new Quiz;
        $quiz->name = $request->name;
        $quiz->description = $request->description;

        if ($quiz->save()) {
            return response()->json([
                'message' => 'Quiz Created',
            ], 201);
        }

        return response()->json([
            'message' => 'Quiz creation failed',
        ], 500);
    }

    public function edit(EditQuizRequest $request, int $id): JsonResponse
    {
        $quiz = Quiz::find($id);

        if ($quiz == []) {
            return response()->json([
                'message' => 'Quiz not found',
            ], 404);
        } else {
            $quiz->name = $request->name;
            $quiz->description = $request->description;
        }

        if ($quiz->save()) {
            return response()->json([
                'message' => 'Quiz Created',
            ], 201);
        }

        return response()->json([
            'message' => 'Quiz editing failed',
        ], 500);
    }
}

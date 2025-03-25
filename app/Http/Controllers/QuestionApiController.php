<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class QuestionApiController extends Controller
{
    public function create(QuestionRequest $request): JsonResponse
    {

        $newQuestion = new Question;

        $newQuestion->question = $request->question;
        $newQuestion->hint = $request->hint;
        $newQuestion->points = $request->points;
        $newQuestion->quiz_id = $request->quiz_id;
        $newQuestion->save();

        if (! $newQuestion->save()) {
            return response()->json([
                'message' => 'Question creation failed',
            ], 500);
        }

        return response()->json([
            'message' => 'Question Created',
        ], 201);
    }

    public function delete(int $id): JsonResponse
    {
        $question = Question::find($id);

        if (! $question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }

        $question->delete();

        return response()->json([
            'message' => 'Question deleted',
        ], 200);
    }

    public function edit(QuestionRequest $request, int $id): JsonResponse
    {
        $question = Question::find($id);

        if (! $question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }

        $question->question = $request->question;
        $question->points = $request->points;
        $question->hint = $request->hint;

        if ($question->save()) {
            return response()->json([
                'message' => 'Question edited',
            ], 200);
        }

        return response()->json([
            'message' => 'Question editing failed',
        ], 500);
    }
}

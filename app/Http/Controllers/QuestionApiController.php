<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class QuestionApiController extends Controller
{
    public function create(CreateQuestionRequest $request): JsonResponse
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
}

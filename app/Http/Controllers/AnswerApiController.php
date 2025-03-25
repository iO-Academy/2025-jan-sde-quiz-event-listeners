<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;

class AnswerApiController extends Controller
{
    public function create(CreateAnswerRequest $request): JsonResponse
    {
        $answer = new Answer;
        $answer->answer = $request->answer;
        $answer->correct = $request->correct;
        $answer->feedback = $request->feedback;
        $answer->question_id = $request->question_id;

        if ($answer->save()) {
            return response()->json([
                'message' => 'Answer created',
            ], 201);
        }

        return response()->json([
            'message' => 'Answer creation failed',
        ], 500);
    }

    public function delete(int $id): JsonResponse
    {
        $answer = Answer::find($id);

        if (! $answer) {
            return response()->json([
                'message' => 'Answer not found',
            ], 404);
        }

        $answer->delete();

        return response()->json([
            'message' => 'Answer deleted',
        ]);
    }
}

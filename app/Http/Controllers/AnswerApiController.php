<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;

class AnswerApiController extends Controller
{
    public function create(AnswerRequest $request): JsonResponse
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

    public function update(AnswerRequest $request, Answer $answer): JsonResponse
    {
        $answer->answer = $request->answer ?? $answer->answer;
        $answer->correct = $request->correct ?? $answer->correct;
        $answer->feedback = $request->feedback ?? $answer->feedback;

        if ($answer->save()) {
            return response()->json([
                'message' => 'Answer edited',
            ],200);
        };

        return response()->json([
            'message' => 'Answer editing failed',
        ], 500);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerApiController extends Controller
{
    public function create(CreateAnswerRequest $request): JsonResponse
    {
        $answer = new Answer();
        $answer->answer = $request->answer;
        $answer->correct = $request->correct;
        $answer->feedback = $request->feedback ?? null;
        $answer->question_id = $request->question_id;
        $answer->save();

        return response()->json([
            'message' => 'Answer created'
        ], 201);
    }
}

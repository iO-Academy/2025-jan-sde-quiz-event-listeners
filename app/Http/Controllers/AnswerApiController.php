<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnswerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerApiController extends Controller
{
    public function create(CreateAnswerRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Answer created'
        ], 201);
    }
}

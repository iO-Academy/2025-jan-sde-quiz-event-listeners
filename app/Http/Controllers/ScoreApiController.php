<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultsRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class ScoreApiController extends Controller
{
    public function results(ResultsRequest $request): JsonResponse
    {

        $question_count = 0;
        $correct_count = 0;
        $available_points = 0;
        $points = 0;

        $answers = $request->answers;

        foreach ($answers as $answer) {
            // increment question count
            $question_count++;
            // retrieve data of the question
            $questionData = Question::findOrFail($answer['question']);
            // add points to available points from each question
            $available_points += $questionData->points;

            // retrieve answer data
            $answerData = Answer::findOrFail($answer['answer']);
            // if the answer is correct
            if ($answerData['correct']) {
                // increment correct count
                $correct_count++;
                // add points of question to our points
                $points += $questionData->points;
            }
        }

        return response()->json([
            'message' => 'Score calculated',
            'data' => [
                'question_count' => $question_count,
                'correct_count' => $correct_count,
                'available_points' => $available_points,
                'points' => $points,
            ],
        ]);
    }
}

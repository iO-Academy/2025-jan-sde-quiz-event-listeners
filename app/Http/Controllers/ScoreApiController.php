<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultsRequest;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;

class ScoreApiController extends Controller
{
    public function results(ResultsRequest $request): JsonResponse
    {

        $question_count = 0;
        $correct_count = 0;
        $available_points = 0;
        $points = 0;

        $quizId = $request->quiz;
        $answers = $request->answers;

        // retrieve all data for that quiz
        // with all questions and answers
        $quiz = Quiz::with('questions.answers')->find($quizId);

        // Loop through $quiz->questions to calculate available points
        // and the number of questions
        foreach ($quiz->questions as $question) {
            $available_points += $question->points;
            $question_count++;
        }

        // Loop through the users answers
        // find the question for the id they sent
        //
        // Calculate points scored for each correct answer
        foreach ($answers as $answer) {
            $question = $quiz->questions->find($answer['question']);
            $answerData = $question->answers->find($answer['answer']);
            if ($answerData && $answerData->correct) {
                $correct_count++;
                $points += $question->points;
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

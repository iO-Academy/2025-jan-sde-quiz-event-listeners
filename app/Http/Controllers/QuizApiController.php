<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizRequest;
use App\Http\Requests\ResultsRequest;
use App\Models\Answer;
use App\Models\Question;
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
        if (! $quiz) {
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

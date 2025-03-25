<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ScoreApiControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_score_api_controller_success_response_quiz_results(): void
    {
        $data = [
            'quiz' => 1,
            'answers' => [
                ['question' => 1, 'answer' => 1],
                ['question' => 2, 'answer' => 1],
                ['question' => 3, 'answer' => 1],
            ],
        ];

        $quiz = Quiz::factory()->create(['id' => 1]);

        $correct = [true, false];
        $points = [1, 2, 3];
        $answerId = 1;

        for ($i = 0; $i < 3; $i++) {

            // creating three questions, all with quiz id of 1
            // their own unique id of $i + 1 (1, 2, 3)
            Question::factory()->create(['id' => $i + 1, 'quiz_id' => $quiz->id, 'points' => $points[$i]]);

            // Nested loop runs twice for each question created
            // and creates two answers for that question
            // question_id matches id of question
            for ($j = 0; $j < 2; $j++) {
                Answer::factory()->create(['question_id' => $i + 1, 'id' => $answerId, 'correct' => $correct[$j]]);
                $answerId++;
            }
        }

        $response = $this->postJson('/api/scores', $data);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $response) {
                $response->hasAll('message', 'data')
                    ->whereType('message', 'string')
                    ->has('data', function (AssertableJson $data) {
                        $data->hasAll('question_count', 'correct_count', 'available_points', 'points')
                            ->whereAllType([
                                'question_count' => 'integer',
                                'correct_count' => 'integer',
                                'available_points' => 'integer',
                                'points' => 'integer',
                            ])
                            ->where('question_count', 3)
                            ->where('correct_count', 3)
                            ->where('available_points', 6)
                            ->where('points', 6);
                    });
            });
    }

    public function test_score_api_controller_invalid_result_data(): void
    {
        $data = [
            'quiz' => 'invalid',
            'answers' => [
                ['question' => 'invalid', 'answer' => 'invalid'],
                ['question' => 'invalid', 'answer' => 'invalid'],
                ['question' => 'invalid', 'answer' => 'invalid'],
            ],
        ];

        $quiz = Quiz::factory()->create(['id' => 1]);

        $correct = [true, false];
        $points = [1, 2, 3];
        $answerId = 1;

        for ($i = 0; $i < 3; $i++) {
            Question::factory()->create(['id' => $i + 1, 'quiz_id' => $quiz->id, 'points' => $points[$i]]);
            for ($j = 0; $j < 2; $j++) {
                Answer::factory()->create(['question_id' => $i + 1, 'id' => $answerId, 'correct' => $correct[$j]]);
                $answerId++;
            }
        }

        $response = $this->postJson('/api/scores', $data);
        $response->assertInvalid([
            'quiz',
            'answers.0.question',
            'answers.1.question',
            'answers.2.question',
            'answers.0.answer',
            'answers.1.answer',
            'answers.2.answer',
        ]);
    }

    public function test_score_api_controller_missing_results_data(): void
    {
        $data = [];
        $response = $this->postJson('/api/scores', $data);
        $response->assertInvalid(['quiz', 'answers']);
    }
}

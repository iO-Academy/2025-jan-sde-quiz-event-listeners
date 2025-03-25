<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QuizApiControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_quiz_api_controller_returns_correct_data(): void
    {
        $quiz = Quiz::factory()->count(5)->create();
        $response = $this->get('/api/quizzes');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $response) {
                $response->hasAll('message', 'data')
                    ->has('data', 5, function (AssertableJson $data) {
                        $data->hasAll('id', 'name', 'description')
                            ->whereAllType([
                                'name' => 'string',
                                'description' => 'string',
                            ]);
                    });
            });
    }

    public function test_quiz_api_controller_no_quizzes_exist(): void
    {
        $response = $this->get('/api/quizzes');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $response) {
                $response->hasAll('message', 'data')
                    ->where('data', []);
            });
    }

    public function test_quiz_api_controller_single_does_not_exist(): void
    {
        $response = $this->get('/api/quizzes/1');
        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $response) {
                $response->where('message', 'Quiz not found');
            });
    }

    public function test_quiz_api_controller_single_does_exist(): void
    {
        $quiz = Quiz::factory()->has(Question::factory()->count(1)
            ->has(Answer::factory()->count(1)))->create(['id' => 1]);

        $response = $this->get('/api/quizzes/1');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $response) {
                $response->hasAll('message', 'data')
                    ->has('data', function (AssertableJson $data) {
                        $data->hasAll('id', 'name', 'description', 'questions')
                            ->has('questions', 1, function (AssertableJson $questions) {
                                $questions->hasAll('question', 'hint', 'points', 'answers', 'id')
                                    ->has('answers', 1, function (AssertableJson $answers) {
                                        $answers->hasAll('id', 'answer', 'feedback', 'correct');
                                    });
                            });
                    });
            });

    }

    public function test_quiz_api_controller_successful_create_quiz(): void
    {
        $quizData = [
            'name' => 'quiz',
            'description' => 'quiz',
        ];
        $response = $this->postJson('/api/quizzes', $quizData);
        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $response) {
                $response->has('message')
                    ->whereType('message', 'string');
            });
        $this->assertDatabaseHas('quizzes', $quizData);
    }

    public function test_quiz_api_controller_invalid_data(): void
    {
        $quizData = [
            'name' => 2,
            'description' => 1,
        ];
        $response = $this->postJson('/api/quizzes', $quizData);
        $response->assertInvalid(['name', 'description']);
    }

    public function test_quiz_api_controller_success_response_quiz_results(): void
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
                            ]);
                    });
            });
    }
}

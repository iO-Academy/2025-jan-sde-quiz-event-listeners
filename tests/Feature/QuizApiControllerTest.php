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
                            ->where('name', fn ($name) => is_string($name))
                            ->where('description', fn ($description) => is_string($description));
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
                                $questions->hasAll('question', 'hint', 'points', 'answers', 'id', 'quiz_id')
                                    ->has('answers', 1, function (AssertableJson $answers) {
                                        $answers->hasAll('id', 'answer', 'feedback', 'correct');
                                    });
                            });
                    });
            });

    }
}

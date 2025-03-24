<?php

namespace Tests\Feature;

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

    public function test_quiz_api_controller_successfulCreateQuiz() : void
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
    }

    public function test_quiz_api_controller_invalidData() : void
    {
        $quizData = [
            'name' => 'quiz',
            'description' => 1,
        ];
        $response = $this->postJson('/api/quizzes', $quizData);
        $response->assertStatus(422);
    }
}

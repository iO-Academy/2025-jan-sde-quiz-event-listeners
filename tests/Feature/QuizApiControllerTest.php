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
}

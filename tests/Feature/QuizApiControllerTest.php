<?php

namespace Tests\Feature;

use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QuizApiControllerTest extends TestCase
{
    use DatabaseMigrations;
    public function test_QuizApiController_returnsCorrectData() : void
    {
        $quiz = Quiz::factory()->count(5)->create();
        $response = $this->get('/api/quizzes');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $response) {
                $response->hasAll('message', 'data');
            });

    }
}

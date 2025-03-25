<?php

namespace Tests\Feature;

use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class QuestionApiControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_question_success(): void
    {
        $quiz = Quiz::factory()->create();

        $testData = [
            'question' => 'What is the square root of 144',
            'points' => '2',
            'hint' => 'This is a hint',
            'quiz_id' => $quiz->id,
        ];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('questions', $testData);
    }

    public function test_create_question_missing_data(): void
    {
        $response = $this->postJson('/api/questions', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['question', 'points', 'quiz_id']);
    }

    public function test_create_question_invalid_data(): void
    {
        $response = $this->postJson('/api/questions', [
            'question' => 'a',
            'points' => 'not an integer',
            'hint' => 'a',
            'quiz_id' => 'not a valid id',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['question', 'points', 'quiz_id']);
    }

    public function test_create_question_non_existing_quiz_id(): void
    {
        $response = $this->postJson('/api/questions', [
            'question' => 'This is a question',
            'points' => 5,
            'hint' => 'Hello there',
            'quiz_id' => 99999,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quiz_id']);
    }
}

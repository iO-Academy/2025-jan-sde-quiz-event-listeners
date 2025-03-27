<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
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

    public function test_delete_question_success(): void
    {
        $question = Question::factory()->create(['id' => 1]);
        $response = $this->deleteJson('/api/questions/1');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($question) {
                $json->has('message')
                    ->where('message', 'Question deleted');
                $this->assertDatabaseMissing('questions', [$question->id]);
            });
    }

    public function test_delete_question_not_found(): void
    {
        Log::shouldReceive('info')->once()->with('404', [
            'method' => 'DELETE',
            'url' => 'http://localhost/api/questions/1',
        ]);

        $response = $this->deleteJson('/api/questions/1');
        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->where('message', 'Question not found');
            });
    }

    public function test_edit_question_not_found(): void
    {
        $questionData = [
            'question' => 'This is a question',
            'points' => 5,
            'hint' => 'Hello there',
        ];

        Log::shouldReceive('info')->once()->with('404', [
            'method' => 'PUT',
            'url' => 'http://localhost/api/questions/1',
        ]);

        $response = $this->putJson('/api/questions/1', $questionData);
        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->where('message', 'Question not found');
            });
    }

    public function test_edit_question_success(): void
    {
        $question = Question::factory()->create(['id' => 1]);
        $questionData = [
            'question' => 'This is a question',
            'points' => 5,
            'hint' => 'Hello there',
        ];
        $response = $this->putJson('/api/questions/1', $questionData);
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->where('message', 'Question edited');
            });
        $this->assertDatabaseHas('questions', $questionData);
    }

    public function test_edit_question_invalid_data(): void
    {
        $question = Question::factory()->create(['id' => 1]);
        $response = $this->putJson('/api/questions/1', [
            'question' => 'a',
            'points' => 'nan',
            'hint' => 'a',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['question', 'points']);
    }
}

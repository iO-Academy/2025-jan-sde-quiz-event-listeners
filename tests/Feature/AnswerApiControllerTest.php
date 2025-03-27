<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AnswerApiControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_answer_api_controller_successful_creation(): void
    {
        $answerData = [
            'answer' => 'answer',
            'correct' => true,
            'question_id' => 1,
            'feedback' => null,
        ];
        $quiz = Quiz::factory()->create(['id' => 1]);
        Question::factory()->create(['id' => 1, 'quiz_id' => $quiz->id]);

        $response = $this->postJson('/api/answers', $answerData);
        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $response) {
                $response->has('message');
            });
        $this->assertDatabaseHas('answers', $answerData);
    }

    public function test_answer_api_controller_invalid_data(): void
    {
        $answerData = [
            'answer' => 1,
            'correct' => 'false',
            'question_id' => 'wrong',
            'feedback' => null,
        ];
        $quiz = Quiz::factory()->create(['id' => 1]);

        $response = $this->postJson('/api/answers', $answerData);
        $response->assertInvalid(['answer', 'correct', 'question_id']);
    }

    public function test_answer_api_controller_question_id_doesnt_exist(): void
    {
        $answerData = [
            'answer' => 'answer',
            'correct' => true,
            'question_id' => 5000,
            'feedback' => null,
        ];
        $quiz = Quiz::factory()->create(['id' => 1]);

        $response = $this->postJson('/api/answers', $answerData);
        $response->assertInvalid(['question_id']);
    }

    public function test_api_answer_controller_empty_data(): void
    {
        $answerData = [];
        $response = $this->postJson('/api/answers', $answerData);
        $response->assertInvalid(['answer', 'correct', 'question_id']);
    }

    public function test_delete_answer_success(): void
    {
        $answer = Answer::factory()->create(['id' => 1]);
        $response = $this->deleteJson('/api/answers/1');
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($answer) {
                $json->has('message')
                    ->where('message', 'Answer deleted');
                $this->assertDatabaseMissing('questions', [$answer->id]);
            });
    }

    public function test_delete_answer_not_found(): void
    {
        Log::shouldReceive('info')->once()->with('404', [
            'method' => 'DELETE',
            'url' => 'http://localhost/api/answers/1',
        ]);

        $response = $this->deleteJson('/api/answers/1');
        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->has('message')
                    ->where('message', 'Answer not found');
            });
    }

    public function test_api_answer_controller_answer_does_not_exist(): void
    {
        Log::shouldReceive('info')->once()->with('404', [
            'method' => 'PUT',
            'url' => 'http://localhost/api/answers/9999',
        ]);

        $response = $this->putJson('/api/answers/9999', [
            'answer' => 'Updated answer',
            'correct' => false,
            'feedback' => 'Updated feedback',
        ]);

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $response) {
                $response->has('message');
            });
    }

    public function test_api_answer_controller_can_update_an_answer(): void
    {
        $question = Question::factory()->create();
        $answer = Answer::factory()->create(['question_id' => $question->id]);

        $testData = [
            'answer' => 'updated answer',
            'correct' => false,
            'feedback' => 'Updated feedback',
            'question_id' => $question->id,
        ];

        $response = $this->putJson("/api/answers/{$answer->id}", $testData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Answer edited']);

        $this->assertDatabaseHas('answers', $testData);
    }

    public function test_api_answer_controller_invalid_data(): void
    {
        $question = Answer::factory()->create(['id' => 1]);

        $response = $this->putJson('/api/answers/1', [
            'feedback' => 'Updated feedback',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['answer', 'correct']);
    }
}

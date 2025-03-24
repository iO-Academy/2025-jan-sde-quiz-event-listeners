<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // each question has 2 answers
        // 50 questions
        // we want 2 answers for each question
        // our relationship is one question has many answers
        // question id == 1, 2, 3
        // for each id we need two answers

        for ($i = 0; $i < 50; $i++){
            // this is running 50 times
            // once for each question
            DB::table('answers')->insert([
                'answer' => fake()->words(5, true),
                'feedback' => fake()->words(5, true),
                'correct' => true,
                'question_id' => $i + 1,
            ]);
            for ($j = 0; $j < 3; $j ++){
                // for each question we are running a nested loop that runs twice
                DB::table('answers')->insert([
                    'answer' => fake()->words(5, true),
                    'feedback' => fake()->words(5, true),
                    'correct' => false,
                    'question_id' => $i + 1,
                ]);

            }

        }




    }
}

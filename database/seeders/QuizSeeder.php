<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('quizzes')->insert([
                'name' => fake()->words(3, true),
                'description' => fake()->paragraphs(1, true),
            ]);
        }
    }
}

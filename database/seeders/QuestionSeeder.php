<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quiz_ids = [1,2,3,4,5,6,7,8,9,10];

        foreach ($quiz_ids as $id) {
            for ($j = 0; $j < 5; $j++) {
                DB::table('questions')->insert([
                    'question' => fake()->words(5, true),
                    'hint' => fake()->words(5, true),
                    'points' => rand(1, 5),
                    'quiz_id' => $id,
                ]);
            }
        }
    }
}

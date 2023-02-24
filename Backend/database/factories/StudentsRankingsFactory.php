<?php

namespace Database\Factories;

use App\Models\Ranking;
use App\Models\Student;
use Faker\Factory;

class StudentsRankingsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'students_id' => Student::factory(),
            'rankings_code' => Ranking::factory(),
            'rank' => fake()->randomNumber(3),
            'points' => fake()->randomNumber(5)
        ];
    }
}

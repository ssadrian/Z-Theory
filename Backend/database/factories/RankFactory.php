<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class RankFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->uuid
        ];
    }
}

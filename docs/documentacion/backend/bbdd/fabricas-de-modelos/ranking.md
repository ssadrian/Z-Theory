<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class RankingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->uuid(),
            'name' => fake()->text(50),
            'creator' => Teacher::all()->random()
        ];
    }
}

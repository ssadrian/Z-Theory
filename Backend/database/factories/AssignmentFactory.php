<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(20),
            'description' => fake()->text(),
            'content' => fake()->text(),
            'points' => fake()->numberBetween(0, 100),
            'creator' => Teacher::all()->random()
        ];
    }
}

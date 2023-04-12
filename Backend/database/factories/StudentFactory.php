<?php

namespace Database\Factories;

use Illuminate\{Database\Eloquent\Factories\Factory, Support\Carbon};

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surnames' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt(fake()->password()),
            'nickname' => fake()->uuid(),
            'birth_date' => Carbon::now()->format('Y-m-d'),
            'avatar' => fake()->text(10),
            'kudos' => fake()->numberBetween(int2: 1_000)
        ];
    }
}

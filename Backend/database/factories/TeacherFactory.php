<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'surnames' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt(fake()->password()),
            'nickname' => fake()->uuid(),
            'avatar' => fake()->text(),
            'center' => fake()->word()
        ];
    }
}

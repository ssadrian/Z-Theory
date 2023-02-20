<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'name' => fake()->name(),
            'surnames' => fake()->word(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt(fake()->password()),
            'nickname' => fake()->word(),
            'avatar' => fake()->text(),
            'center' => fake()->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

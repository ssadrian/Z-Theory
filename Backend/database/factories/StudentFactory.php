<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'surnames' => fake()->word(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt(fake()->password()),
            'nickname' => fake()->word(),
            'birth_date' => Carbon::now()->format('Y-m-d'),
            'avatar' => fake()->text(10)
        ];
    }
}

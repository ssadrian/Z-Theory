<?php

namespace Database\Seeders;

use App\Models\Ranking;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            DefaultAvatarSeeder::class
        ]);

        // Test student
        Student::factory()
            ->create(attributes: [
                'name' => fake()->name(),
                'surnames' => fake()->word(),
                'email' => 'q@q',
                'password' => bcrypt('q'),
                'nickname' => fake()->word(),
                'birth_date' => Carbon::now()->format('Y-m-d'),
                'avatar' => fake()->text(10)
            ]);

        // Test teacher
        Teacher::factory()
            ->has(Ranking::factory(), 'rankingsCreated')
            ->create(attributes: [
                'name' => fake()->name(),
                'surnames' => fake()->word(),
                'email' => 'w@w',
                'password' => bcrypt('w'),
                'nickname' => fake()->text(10),
                'center' => fake()->word(),
                'avatar' => fake()->text(10)
            ]);

        Student::factory(9)
            ->create();

        Teacher::factory(9)
            ->has(Ranking::factory(), 'rankingsCreated')
            ->create();

        foreach (Student::all() as $student) {
            attachRandomRanking:
            $student->rankings()->attach(
                Ranking::all()->random(),
                [
                    'points' => fake()->numberBetween(int2: 50)
                ]
            );

            // 10% possibility of adding the student to another ranking
            // as well could result in integrity errors
            if (fake()->numberBetween(int2: 9) == 0) {
                goto attachRandomRanking;
            }
        }
    }
}

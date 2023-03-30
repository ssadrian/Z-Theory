<?php

namespace Database\Seeders;

use App\{Models\Assignment, Models\Ranking, Models\Student, Models\Teacher};
use Illuminate\{Database\Seeder, Support\Carbon, Support\Facades\DB};

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
                'surnames' => fake()->lastName(),
                'email' => 'q@q',
                'password' => bcrypt('q'),
                'nickname' => fake()->uuid(),
                'birth_date' => Carbon::now()->format('Y-m-d'),
                'avatar' => fake()->text(10)
            ]);

        // Test teacher
        Teacher::factory()
            ->has(Ranking::factory(), 'rankingsCreated')
            ->create(attributes: [
                'name' => fake()->name(),
                'surnames' => fake()->lastName(),
                'email' => 'w@w',
                'password' => bcrypt('w'),
                'nickname' => fake()->uuid(),
                'center' => fake()->word(),
                'avatar' => fake()->text(10)
            ]);

        Student::factory(9)
            ->create();

        Teacher::factory(9)
            ->has(Ranking::factory(), 'rankingsCreated')
            ->create();

        Assignment::factory(10)
            ->create([
                'status' => DB::select('SELECT * FROM assignment_statuses ORDER BY RAND() LIMIT 1')
            ]);

        foreach (Student::all() as $student) {
            foreach (range(0, 2) as $ignored) {
                $randomRank = Ranking::all()->random();
                $pivot = [
                    'points' => fake()->numberBetween(int2: 50)
                ];

                $student->rankings()->attach($randomRank, $pivot);

                // 10% possibility of adding the student to another ranking
                $rolledNumber = fake()->numberBetween(int2: 9);
                if ($rolledNumber != 0) {
                    break;
                }
            }
        }
    }
}

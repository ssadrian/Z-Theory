<?php

namespace Database\Seeders;

use App\{Models\Ranking, Models\Student, Models\Teacher};
use Illuminate\{Database\Seeder, Support\Carbon};

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

        $this->command->info('Creating test student');
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

        $this->command->info('Creating test teacher');
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

        $this->command->info('Creating other 9 students');
        Student::factory(9)
            ->create();

        $this->command->info('Creating other 9 teachers');
        Teacher::factory(9)
            ->has(Ranking::factory(), 'rankingsCreated')
            ->create();

        $this->command->info('Assigning ranks to students');
        foreach (Student::all() as $student) {
            foreach (range(0, 9) as $ignored) {
                $randomRank = Ranking::all()->random();
                $pivot = [
                    'points' => fake()->numberBetween(int2: 50)
                ];

                $this->command->info('Attaching to student ' . $student['id'] . ' rank ' . $randomRank['code'] . ' with points ' . $pivot['points']);
                $student->rankings()->attach($randomRank, $pivot);

                // 10% possibility of adding the student to another ranking
                $this->command->info('Rolling random number');
                $rolledNumber = fake()->numberBetween(int2: 9);
                $this->command->info('Rolled number ' . $rolledNumber);
                if ($rolledNumber == 0) {
                    $this->command->info('Attaching new ranking to same student');
                }
            }

            $this->command->info('Going to next student');
        }
    }
}

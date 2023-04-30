<?php

namespace Database\Seeders;

use App\{Models\Assignment, Models\Ranking, Models\Skill, Models\Student, Models\Teacher};
use App\Http\Controllers\EvaluationController;
use App\Models\StudentSkillRanking;
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
            DefaultAvatarSeeder::class,
            SkillSeeder::class
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

        // Test user
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
            ->create();

        foreach (Student::all() as $student) {
            foreach (range(0, 2) as $ignored) {
                $randomRank = Ranking::all()->random();
                $pivot = [
                    'points' => fake()->numberBetween(int2: 50),
                    'kudos' => fake()->numberBetween(int2: 1_000)
                ];

                $student->rankings()->attach($randomRank, $pivot);

                $this->addRankingSkillsForStudent($student, $randomRank);

                // 10% possibility of adding the student to another ranking
                $rolledNumber = fake()->numberBetween(int2: 9);
                if ($rolledNumber != 0) {
                    break;
                }
            }
        }
    }

    private function addRankingSkillsForStudent($student, Ranking $ranking): void
    {
        $skillId = Skill::all()->count();

        // Add the needed relationships with the skills
        while ($skillId > 0) {
            StudentSkillRanking::create([
                'student_id' => $student->id,
                'skill_id' => $skillId,
                'ranking_id' => $ranking->id,
                'kudos' => random_int(0, 10_000),
                'created_at' => time(),
                'updated_at' => time()
            ]);

            $skillId -= 1;
        }
    }
}

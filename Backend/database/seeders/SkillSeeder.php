<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert(/** @lang MariaDB */ "
            INSERT INTO skills (name)
                   VALUES ('Responsibility'),
                          ('Cooperation'),
                          ('Autonomy'),
                          ('Initiative'),
                          ('Emotional');
        ");

        DB::update(/** @lang MariaDB */ "
            UPDATE skills
               SET created_at = NOW()
                 , updated_at = NOW()
            WHERE name != '';
        ");
    }
}

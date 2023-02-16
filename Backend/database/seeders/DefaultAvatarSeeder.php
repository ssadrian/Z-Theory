<?php

namespace Database\Seeders;

use Database\Factories\DefaultAvatarFactory;
use Illuminate\Database\Seeder;

class DefaultAvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DefaultAvatarFactory::new()->create();
        DefaultAvatarFactory::new()->create();
    }
}

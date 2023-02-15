<?php

namespace Database\Seeders;

use Database\Factories\DefaultProfilePictureFactory;
use Illuminate\Database\Seeder;

class DefaultProfilePictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DefaultProfilePictureFactory::new()->create();
        DefaultProfilePictureFactory::new()->create();
    }
}

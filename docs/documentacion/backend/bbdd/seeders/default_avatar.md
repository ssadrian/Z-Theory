<?php

namespace Database\Seeders;

use App\Models\DefaultAvatar;
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
        DefaultAvatar::factory()->create();
        DefaultAvatar::factory()->create();
    }
}

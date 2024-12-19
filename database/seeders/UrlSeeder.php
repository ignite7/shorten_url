<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! $user = User::unsafeInstance(User::query()->firstWhere('email', UserFactory::REGULAR_EMAIL))) {
            return;
        }

        Url::factory(5)->for($user)->create();
    }
}

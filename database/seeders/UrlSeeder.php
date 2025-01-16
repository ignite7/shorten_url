<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Url;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

final class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::unsafeInstance(User::query()->firstWhere('email', UserFactory::REGULAR_EMAIL));

        if (! $user instanceof User) {
            return;
        }

        Url::factory(5)->for($user)->create();
    }
}

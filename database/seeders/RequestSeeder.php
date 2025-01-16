<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

final class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::unsafeInstance(User::query()->firstWhere('email', UserFactory::REGULAR_EMAIL));

        if ($user instanceof User) {
            $ipv4 = fake()->ipv4();
            $userAgent = fake()->userAgent();
            $user->urls->chunk(100)->each(static function (Collection $urls) use ($user, $ipv4, $userAgent): void {
                $urls->each(static function (Url $url) use ($user, $ipv4, $userAgent): void {
                    Request::factory()->for($url)->for($user)->create([
                        'ip_address' => $ipv4,
                        'user_agent' => $userAgent,
                    ]);
                });
            });
        }

        Request::factory(10)->regularUser()->create();
        Request::factory(5)->withoutUser()->create();
    }
}

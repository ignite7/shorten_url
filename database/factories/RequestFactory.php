<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\HttpMethod;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Database\Factories\Traits\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Request>
 */
final class RequestFactory extends Factory
{
    /**
     * @use RefreshOnCreate<Request>
     */
    use RefreshOnCreate;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url_id' => Url::factory(),
            'user_id' => User::factory(),
            'anon_token' => null,
            'method' => fake()->randomElement(HttpMethod::values()),
            'uri' => fake()->url(),
            'query' => collect(),
            'headers' => collect(),
            'body' => collect(),
            'ip_address' => fake()->randomElement([fake()->ipv4(), fake()->ipv6()]),
            'user_agent' => fake()->userAgent(),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state(fn (array $attributes): array => [
            'url_id' => Url::factory()->withoutUser(),
            'user_id' => null,
            'anon_token' => fake()->uuid(),
        ]);
    }

    public function regularUser(): static
    {
        return $this->state(function (): array {
            $user = User::factory()->regularRole()->create();

            return [
                'url_id' => Url::factory()->create(['user_id' => $user]),
                'user_id' => $user,
            ];
        });
    }
}

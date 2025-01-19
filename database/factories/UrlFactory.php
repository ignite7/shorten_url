<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use Database\Factories\Traits\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Url>
 */
final class UrlFactory extends Factory
{
    /**
     * @use RefreshOnCreate<Url>
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
            'user_id' => User::factory(),
            'source' => fake()->url(),
        ];
    }

    public function withoutUser(): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => null,
        ]);
    }
}

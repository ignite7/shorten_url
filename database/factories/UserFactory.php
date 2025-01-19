<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Database\Factories\Traits\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * @use RefreshOnCreate<User>
     */
    use RefreshOnCreate;

    public const string ADMIN_EMAIL = 'admin@shortenurl.io';

    public const string STAFF_EMAIL = 'staff@shortenurl.io';

    public const string REGULAR_EMAIL = 'regular@shortenurl.io';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->randomElement(UserRole::values()),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password1234!', // Min 12 characters
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): self
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::ADMIN->value,
            'email' => self::ADMIN_EMAIL,
        ]);
    }

    public function staff(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::STAFF->value,
            'email' => self::STAFF_EMAIL,
        ]);
    }

    public function regular(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::REGULAR->value,
            'email' => self::REGULAR_EMAIL,
        ]);
    }

    public function adminRole(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::ADMIN->value,
        ]);
    }

    public function staffRole(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::STAFF->value,
        ]);
    }

    public function regularRole(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::REGULAR->value,
        ]);
    }
}

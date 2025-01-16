<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    public const string ADMIN_EMAIL = 'admin@shortenurl.io';

    public const string STAFF_EMAIL = 'staff@shortenurl.io';

    public const string REGULAR_EMAIL = 'regular@shortenurl.io';

    /**
     * The current password being used by the factory.
     */
    private static ?string $password = null;

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

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return $this
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::ADMIN->value,
            'email' => self::ADMIN_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::STAFF->value,
            'email' => self::STAFF_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function regular(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::REGULAR->value,
            'email' => self::REGULAR_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function regularRole(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => UserRole::REGULAR->value,
        ]);
    }
}

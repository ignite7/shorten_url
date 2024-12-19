<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return $this
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::ADMIN->value,
            'email' => static::ADMIN_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::STAFF->value,
            'email' => static::STAFF_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function regular(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::REGULAR->value,
            'email' => static::REGULAR_EMAIL,
        ]);
    }

    /**
     * @return $this
     */
    public function regularRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::REGULAR->value,
        ]);
    }
}

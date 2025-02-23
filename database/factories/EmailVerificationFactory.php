<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\EmailVerification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EmailVerification>
 */
final class EmailVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'verification_code' => Str::random(6),
            'expires_at' => now()->addHours(3),
        ];
    }
}

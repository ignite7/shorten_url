<?php

declare(strict_types=1);

use App\Models\EmailVerification;

it('can get the email verification in an array format', function (): void {
    $emailVerification = EmailVerification::factory()->create();

    expect($emailVerification->toArray())->toBe([
        'email' => $emailVerification->email,
        'verification_code' => $emailVerification->verification_code,
        'expires_at' => $emailVerification->expires_at->toISOString(),
        'created_at' => $emailVerification->created_at?->toISOString(),
    ]);
});

<?php

declare(strict_types=1);

use App\Mail\SendVerificationCode;
use App\Models\EmailVerification;

test('mailable content', function (): void {
    $verificationCode = Str::random(6);
    $emailVerification = EmailVerification::factory()->create([
        'verification_code' => bcrypt($verificationCode),
    ]);

    $mailable = new SendVerificationCode($emailVerification->email, $verificationCode);

    expect($mailable->content()->markdown)->toBe('mail.auth.sendVerificationCode');
    $mailable->assertSeeInHtml($verificationCode);
    $mailable->assertSeeInHtml($emailVerification->email);
});

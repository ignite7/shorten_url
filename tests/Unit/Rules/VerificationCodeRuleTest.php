<?php

declare(strict_types=1);

use App\Models\EmailVerification;
use App\Rules\VerificationCodeRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

it('passes when the email and verification code are correct', function (): void {
    $verificationCode = Str::random(6);
    $emailVerification = EmailVerification::factory()->create([
        'verification_code' => Hash::make($verificationCode),
    ]);

    $rule = new VerificationCodeRule();
    $validator = Validator::make([
        'email' => $emailVerification->email,
        'verification_code' => $verificationCode,
    ], [
        'verification_code' => [$rule->setData(['email' => $emailVerification->email])],
    ]);

    expect($validator->passes())->toBeTrue();
});

it('fails when the email is not found', function (): void {
    $rule = new VerificationCodeRule();
    $validator = Validator::make([
        'email' => 'notfound@example.com',
        'verification_code' => Str::random(6),
    ], [
        'verification_code' => [$rule->setData(['email' => 'notfound@example.com'])],
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('verification_code'))->toBe('The verification code field is not valid.');
});

it('fails when the verification code is incorrect', function (): void {
    $emailVerification = EmailVerification::factory()->create([
        'verification_code' => Hash::make('std123'),
    ]);

    $rule = new VerificationCodeRule();
    $validator = Validator::make([
        'email' => $emailVerification->email,
        'verification_code' => 'abc123',
    ], [
        'verification_code' => [$rule->setData(['email' => $emailVerification->email])],
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('verification_code'))->toBe('The verification code field is not valid.');
});

it('fails when the verification code has expired', function (): void {
    $verificationCode = Str::random(6);
    $emailVerification = EmailVerification::factory()->create([
        'verification_code' => Hash::make($verificationCode),
        'expires_at' => now()->subMinutes(5),
    ]);

    $rule = new VerificationCodeRule();
    $validator = Validator::make([
        'email' => $emailVerification->email,
        'verification_code' => $verificationCode,
    ], [
        'verification_code' => [$rule->setData(['email' => $emailVerification->email])],
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('verification_code'))->toBe('The verification code field is not valid.');
});

<?php

declare(strict_types=1);

use App\Actions\Auth\SendVerificationCodeEmail;
use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Mail\SendVerificationCode;
use App\Models\EmailVerification;
use App\Models\User;
use App\Validations\EmailValidation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

beforeEach(function (): void {
    $this->route = route('send-verification-code');
});

describe('as controller', function (): void {
    it('can send verification code email', function (): void {
        $email = fake()->safeEmail();
        Mail::fake();

        $this->post($this->route, [
            'email' => $email,
        ])->assertRedirect();

        $this->assertDatabaseHas(EmailVerification::class, [
            'email' => $email,
        ]);

        Mail::assertQueued(SendVerificationCode::class, static fn (SendVerificationCode $mail): bool => $mail->assertTo($email) && $mail->assertHasSubject('Email Verification Code'));

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Verification code sent successfully.');
    });

    it('can update and sent verification code email', function (): void {
        $email = fake()->safeEmail();
        $expiresAt = now()->addHours(2);
        $emailVerification = EmailVerification::factory()->create([
            'email' => $email,
            'verification_code' => Str::random(6),
            'expires_at' => $expiresAt,
        ]);
        Mail::fake();

        $this->post($this->route, [
            'email' => $email,
        ])->assertRedirect();

        $this->assertDatabaseMissing(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
            'expires_at' => $expiresAt,
        ]);
        $this->assertDatabaseHas(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
        ]);

        Mail::assertQueued(SendVerificationCode::class, static fn (SendVerificationCode $mail): bool => $mail->assertTo($email) && $mail->assertHasSubject('Email Verification Code'));

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Verification code sent successfully.');
    });

    it('cannot sent verification code email if there is no email', function (): void {
        Mail::fake();

        $this->post($this->route)
            ->assertInvalid([
                'email' => 'The email address field is required.',
            ]);

        $this->assertDatabaseCount(EmailVerification::class, 0);

        Mail::assertNothingQueued();
    });

    it('cannot sent verification code email if the email is not valid', function (): void {
        Mail::fake();

        $this->post($this->route, [
            'email' => 'invalid-email',
        ])
            ->assertInvalid([
                'email' => 'The email address field must be a valid email address.',
            ]);

        $this->assertDatabaseCount(EmailVerification::class, 0);

        Mail::assertNothingQueued();
    });

    it('cannot sent verification code email if the email already exists', function (): void {
        $user = User::factory()->regularRole()->create();
        Mail::fake();

        $this->post($this->route, [
            'email' => $user->email,
        ])
            ->assertInvalid([
                'email' => 'The email address has already been taken.',
            ]);

        $this->assertDatabaseCount(EmailVerification::class, 0);

        Mail::assertNothingQueued();
    });

    it('allows up to three requests in five minutes and blocks the fourth', function (): void {
        $email = fake()->safeEmail();
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->post($this->route, ['email' => $email])->assertRedirect();
            expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->not->toBe(FlashMessageType::ERROR->value)
                ->and(Session::get(FlashHelper::MESSAGE_KEY))->not->toBe('Too many requests, please try again later.');
        }

        $this->post($this->route, ['email' => $email])->assertRedirect();
        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Too many requests, please try again later.');
    });

    it('allows requests again after five minutes', function (): void {
        $email = fake()->safeEmail();
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->post($this->route, ['email' => $email])->assertRedirect();
            expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->not->toBe(FlashMessageType::ERROR->value)
                ->and(Session::get(FlashHelper::MESSAGE_KEY))->not->toBe('Too many requests, please try again later.');
        }

        $this->post($this->route, ['email' => $email])->assertRedirect();
        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Too many requests, please try again later.');

        $this->travel(5)->minutes();

        $this->post($this->route, ['email' => $email])->assertRedirect();
        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->not->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->not->toBe('Too many requests, please try again later.');
    });
});

describe('as object', function (): void {
    it('can send verification code email', function (): void {
        $email = fake()->safeEmail();
        Mail::fake();

        $emailVerification = SendVerificationCodeEmail::run($email);

        $this->assertDatabaseHas(EmailVerification::class, [
            'email' => $email,
        ]);

        expect($emailVerification->email)->toBe($email);

        Mail::assertQueued(SendVerificationCode::class, static fn (SendVerificationCode $mail): bool => $mail->assertTo($email) && $mail->assertHasSubject('Email Verification Code'));
    });

    it('can update and sent verification code email', function (): void {
        $email = fake()->safeEmail();
        $expiresAt = now()->addHours(2);
        $emailVerification = EmailVerification::factory()->create([
            'email' => $email,
            'verification_code' => Str::random(6),
            'expires_at' => $expiresAt,
        ]);
        Mail::fake();

        $newEmailVerification = SendVerificationCodeEmail::run($email);

        $this->assertDatabaseMissing(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
            'expires_at' => $expiresAt,
        ]);
        $this->assertDatabaseHas(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
        ]);

        expect($newEmailVerification->email)->toBe($email)
            ->and($newEmailVerification->verification_code)->not()->toBe($emailVerification->verification_code)
            ->and($newEmailVerification->expires_at)->not()->toBe($expiresAt);

        Mail::assertQueued(SendVerificationCode::class, static fn (SendVerificationCode $mail): bool => $mail->assertTo($email) && $mail->assertHasSubject('Email Verification Code'));
    });
});

it('has rules', function (): void {
    $action = new SendVerificationCodeEmail();

    expect($action->rules())->toBeArray()
        ->and($action->rules())->toMatchArray(EmailValidation::rules());
});

it('has validation attributes', function (): void {
    $action = new SendVerificationCodeEmail();

    expect($action->getValidationAttributes())->toBeArray()
        ->and($action->getValidationAttributes())->toBe(EmailValidation::validationAttributes());
});

it('has middlewares', function (): void {
    $action = new SendVerificationCodeEmail();

    expect($action->getControllerMiddleware())->toBeArray()
        ->and($action->getControllerMiddleware())->toBe([
            'guest',
            'throttle:3,5',
        ]);
});

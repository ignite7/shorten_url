<?php

declare(strict_types=1);

use App\Actions\Auth\DeleteEmailVerification;
use App\Actions\Auth\Signup;
use App\Actions\Urls\TransferAnonymousUrlsToUser;
use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Enums\HttpMethod;
use App\Enums\UserRole;
use App\Helpers\FlashHelper;
use App\Models\EmailVerification;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use App\Rules\VerificationCodeRule;
use App\Validations\EmailValidation;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

beforeEach(function (): void {
    $this->route = route('signup');
});

describe('as controller', function (): void {
    it('can register a new user, delete email verification and transfer anonymous urls to user', function (): void {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $email = fake()->safeEmail();
        $verificationCode = Str::random(6);
        $emailVerification = EmailVerification::factory()->create([
            'email' => $email,
            'verification_code' => $verificationCode,
        ]);
        $anonymousToken = fake()->uuid();
        $url = Url::factory()->withoutUser()->create([
            'anonymous_token' => $anonymousToken,
        ]);
        $request = Request::factory()->withoutUser()->create([
            'url_id' => $url->id,
            'anonymous_token' => $anonymousToken,
        ]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->post($this->route, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => 'password123456',
                'password_confirmation' => 'password123456',
                'verification_code' => $verificationCode,
            ])
            ->assertRedirect(route('home'));

        $user = User::instance(User::query()->firstWhere('email', $email));

        expect($user)->not->toBeNull()
            ->and($user->email_verified_at)->not->toBeNull();

        $this->assertDatabaseHas(User::class, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'role' => UserRole::REGULAR->value,
        ]);
        $this->assertDatabaseMissing(EmailVerification::class, [
            'id' => $emailVerification->id,
            'email' => $email,
        ]);
        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'user_id' => null,
            'anonymous_token' => $anonymousToken,
        ]);
        $this->assertDatabaseMissing(Request::class, [
            'id' => $request->id,
            'user_id' => null,
            'anonymous_token' => $anonymousToken,
        ]);
        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'user_id' => $user->id,
            'anonymous_token' => null,
        ]);
        $this->assertDatabaseHas(Request::class, [
            'id' => $request->id,
            'user_id' => $user->id,
            'anonymous_token' => null,
        ]);

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Your account has been created!');
    });

    describe('validating rules', function (): void {
        describe('first_name', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'first_name' => 'The first name field is required.',
                    ]);
            });

            it('must be string', function (): void {
                $this->post($this->route, [
                    'first_name' => 123,
                ])
                    ->assertInvalid([
                        'first_name' => 'The first name field must be a string.',
                    ]);
            });

            it('must be min 2 characters', function (): void {
                $this->post($this->route, [
                    'first_name' => 'a',
                ])
                    ->assertInvalid([
                        'first_name' => 'The first name field must be at least 2 characters.',
                    ]);
            });

            it('must be max 255 characters', function (): void {
                $this->post($this->route, [
                    'first_name' => Str::random(256),
                ])
                    ->assertInvalid([
                        'first_name' => 'The first name field must not be greater than 255 characters.',
                    ]);
            });
        });

        describe('last_name', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'last_name' => 'The last name field is required.',
                    ]);
            });

            it('must be string', function (): void {
                $this->post($this->route, [
                    'last_name' => 123,
                ])
                    ->assertInvalid([
                        'last_name' => 'The last name field must be a string.',
                    ]);
            });

            it('must be min 2 characters', function (): void {
                $this->post($this->route, [
                    'last_name' => 'a',
                ])
                    ->assertInvalid([
                        'last_name' => 'The last name field must be at least 2 characters.',
                    ]);
            });

            it('must be max 255 characters', function (): void {
                $this->post($this->route, [
                    'last_name' => Str::random(256),
                ])
                    ->assertInvalid([
                        'last_name' => 'The last name field must not be greater than 255 characters.',
                    ]);
            });
        });

        describe('email', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'email' => 'The email address field is required.',
                    ]);
            });

            it('must be a valid email', function (): void {
                $this->post($this->route, [
                    'email' => 'invalid-email',
                ])
                    ->assertInvalid([
                        'email' => 'The email address field must be a valid email address.',
                    ]);
            });

            it('must be unique', function (): void {
                $email = fake()->safeEmail();
                User::factory()->create([
                    'email' => $email,
                ]);

                $this->post($this->route, [
                    'email' => $email,
                ])
                    ->assertInvalid([
                        'email' => 'The email address has already been taken.',
                    ]);
            });
        });

        describe('password', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'password' => 'The password field is required.',
                    ]);
            });

            it('must be string', function (): void {
                $this->post($this->route, [
                    'password' => 123,
                ])
                    ->assertInvalid([
                        'password' => 'The password field must be a string.',
                    ]);
            });

            it('must be min 12 characters', function (): void {
                $this->post($this->route, [
                    'password' => 'password',
                ])
                    ->assertInvalid([
                        'password' => 'The password field must be at least 12 characters.',
                    ]);
            });

            it('must be max 255 characters', function (): void {
                $this->post($this->route, [
                    'password' => Str::random(256),
                ])
                    ->assertInvalid([
                        'password' => 'The password field must not be greater than 255 characters.',
                    ]);
            });
        });

        describe('password_confirmation', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'password_confirmation' => 'The password confirmation field is required.',
                    ]);
            });

            it('must be same as password', function (): void {
                $this->post($this->route, [
                    'password' => 'password123456',
                    'password_confirmation' => 'password1234567',
                ])
                    ->assertInvalid([
                        'password_confirmation' => 'The password confirmation field must match password.',
                    ]);
            });
        });

        describe('verification_code', function (): void {
            it('must be required', function (): void {
                $this->post($this->route)
                    ->assertInvalid([
                        'verification_code' => 'The verification code field is required.',
                    ]);
            });

            it('must be string', function (): void {
                $this->post($this->route, [
                    'verification_code' => 123,
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field must be a string.',
                    ]);
            });

            it('must be min 6 characters', function (): void {
                $this->post($this->route, [
                    'verification_code' => '12345',
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field must be at least 6 characters.',
                    ]);
            });

            it('must be max 6 characters', function (): void {
                $this->post($this->route, [
                    'verification_code' => '1234567',
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field must not be greater than 6 characters.',
                    ]);
            });

            it('must be a valid verification code if email does not exists', function (): void {
                $this->post($this->route, [
                    'verification_code' => 'invalid-code',
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field is not valid.',
                    ]);
            });

            it('must be a valid verification code if code does not match', function (): void {
                $email = fake()->safeEmail();
                EmailVerification::factory()->create([
                    'email' => $email,
                    'verification_code' => Str::random(6),
                ]);

                $this->post($this->route, [
                    'email' => $email,
                    'verification_code' => 'abc123',
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field is not valid.',
                    ]);
            });

            it('must be a valid verification code if code is expired', function (): void {
                $email = fake()->safeEmail();
                $verificationCode = Str::random(6);
                EmailVerification::factory()->create([
                    'email' => $email,
                    'expires_at' => now()->subHour(),
                    'verification_code' => $verificationCode,
                ]);

                $this->post($this->route, [
                    'email' => $email,
                    'verification_code' => $verificationCode,
                ])
                    ->assertInvalid([
                        'verification_code' => 'The verification code field is not valid.',
                    ]);
            });
        });
    });
});

describe('as object',
    /**
     * @throws Throwable
     */
    function (): void {
        it('can register a new user, delete email verification and transfer anonymous urls to user', function (): void {
            Queue::fake();

            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            $email = fake()->safeEmail();
            $verificationCode = Str::random(6);
            $emailVerification = EmailVerification::factory()->create([
                'email' => $email,
                'verification_code' => $verificationCode,
            ]);
            $anonymousToken = fake()->uuid();
            $url = Url::factory()->withoutUser()->create([
                'anonymous_token' => $anonymousToken,
            ]);
            $request = Request::factory()->withoutUser()->create([
                'url_id' => $url->id,
                'anonymous_token' => $anonymousToken,
            ]);

            $actionRequest = ActionRequest::create(
                $this->route,
                HttpMethod::POST->value,
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'password' => 'password123456',
                    'password_confirmation' => 'password123456',
                    'verification_code' => $verificationCode,
                ],
                [
                    CookieKey::ANONYMOUS_TOKEN->value => $anonymousToken,
                ]
            );

            $user = Signup::run($actionRequest);

            expect($user->email_verified_at)->not->toBeNull();

            $this->assertDatabaseHas(User::class, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'role' => UserRole::REGULAR->value,
            ]);

            DeleteEmailVerification::assertPushed(1, static function (DeleteEmailVerification $job, array $args) use ($email): bool {
                $job->handle($email);

                return $args[0] === $email;
            });

            TransferAnonymousUrlsToUser::assertPushed(1, static function (TransferAnonymousUrlsToUser $job, array $args) use ($anonymousToken, $user): bool {
                $job->handle($anonymousToken, $user->id);

                return $args[0] === $anonymousToken && $args[1] === $user->id;
            });

            $this->assertDatabaseMissing(EmailVerification::class, [
                'id' => $emailVerification->id,
                'email' => $email,
            ]);
            $this->assertDatabaseMissing(Url::class, [
                'id' => $url->id,
                'user_id' => null,
                'anonymous_token' => $anonymousToken,
            ]);
            $this->assertDatabaseMissing(Request::class, [
                'id' => $request->id,
                'user_id' => null,
                'anonymous_token' => $anonymousToken,
            ]);
            $this->assertDatabaseHas(Url::class, [
                'id' => $url->id,
                'user_id' => $user->id,
                'anonymous_token' => null,
            ]);
            $this->assertDatabaseHas(Request::class, [
                'id' => $request->id,
                'user_id' => $user->id,
                'anonymous_token' => null,
            ]);
        });
    });

it('has rules', function (): void {
    $signup = new Signup();

    expect($signup->rules())->toMatchArray([
        'first_name' => ['required', 'string', 'min:2', 'max:255'],
        'last_name' => ['required', 'string', 'min:2', 'max:255'],
        ...EmailValidation::rules(),
        'password' => ['required', 'string', 'min:12', 'max:255'],
        'password_confirmation' => ['required', 'same:password'],
        'verification_code' => [
            'required',
            'string',
            'min:6',
            'max:6',
            new VerificationCodeRule,
        ],
    ]);
});

it('has validation attributes', function (): void {
    $signup = new Signup();

    expect($signup->getValidationAttributes())->toBe([
        'first_name' => 'first name',
        'last_name' => 'last name',
        ...EmailValidation::validationAttributes(),
        'password_confirmation' => 'password confirmation',
        'verification_code' => 'verification code',
    ]);
});

it('has middlewares', function (): void {
    $signup = new Signup();

    expect($signup->getControllerMiddleware())->toBeArray()
        ->and($signup->getControllerMiddleware())->toBe(['guest']);
});

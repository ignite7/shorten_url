<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Urls\TransferAnonymousUrlsToUser;
use App\Enums\CookieKey;
use App\Enums\UserRole;
use App\Helpers\FlashHelper;
use App\Models\User;
use App\Rules\VerificationCodeRule;
use App\Validations\EmailValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class Signup
{
    use AsController, AsObject;

    /**
     * @param  ActionRequest  $request
     * @return User
     */
    public function handle(ActionRequest $request): User
    {
        $user = User::query()->create([
            'first_name' => $request->string('first_name'),
            'last_name' => $request->string('last_name'),
            'email' => $request->string('email'),
            'password' => $request->string('password'),
            'role' => UserRole::REGULAR->value,
            'email_verified_at' => now(),
        ]);

        DeleteEmailVerification::dispatch($user->email);

        $anonymousToken = $request->cookie(CookieKey::ANONYMOUS_TOKEN->value);
        TransferAnonymousUrlsToUser::dispatchIf(
            Str::isUuid($anonymousToken),
            $anonymousToken,
            $user->id
        );

        return $user;
    }

    /**
     * @return string[]
     */
    public function getControllerMiddleware(): array
    {
        return ['guest'];
    }

    /**
     * @return non-empty-array<string, list<VerificationCodeRule|Unique|string>>
     */
    public function rules(): array
    {
        return [
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
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getValidationAttributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            ...EmailValidation::validationAttributes(),
            'password_confirmation' => 'password confirmation',
            'verification_code' => 'verification code',
        ];
    }

    /**
     * @param  ActionRequest  $request
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->handle($request);

        FlashHelper::message('Your account has been created!');

        return to_route('home');
    }
}

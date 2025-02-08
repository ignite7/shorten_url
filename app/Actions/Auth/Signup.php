<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

// TODO: add tests when working on the feature
// @codeCoverageIgnoreStart
final class Signup
{
    use AsController, AsObject;

    /**
     * @param ActionRequest $request
     * @return User
     */
    public function handle(ActionRequest $request): User
    {
        return User::query()->create([
            'first_name' => $request->string('first_name'),
            'last_name' => $request->string('last_name'),
            'email' => $request->string('email'),
            'password' => $request->string('password'),
            'role' => UserRole::REGULAR->value,
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return string[]
     */
    public function getControllerMiddleware(): array
    {
        return ['guest'];
    }

    /**
     * @return array<string, list<Unique|string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', 'min:12', 'max:255'],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }

    /**
     * @param ActionRequest $request
     * @return RedirectResponse
     */
    public function asController(ActionRequest $request): RedirectResponse
    {
        $this->handle($request);

        return to_route('dashboard');
    }
}
// @codeCoverageIgnoreEnd

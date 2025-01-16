<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

final class Signup
{
    use AsController;

    public function handle(array $data): User
    {
        return User::query()->create([
            ...$data,
            'role' => UserRole::REGULAR->value,
        ]);
    }

    public function getControllerMiddleware(): array
    {
        return ['guest'];
    }

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

    public function asController(ActionRequest $request): RedirectResponse
    {
        $data = $request->except('password_confirmation');
        $this->handle($data);

        return to_route('dashboard');
    }
}

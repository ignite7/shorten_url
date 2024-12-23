<?php

namespace App\Actions\Guest\User;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Signup
{
    use AsAction;

    public function handle(array $data): void
    {
        User::query()->create([
            ...$data,
            'role' => UserRole::REGULAR->value,
        ]);
    }

    public function getControllerMiddleware(): array
    {
        return ['guest'];
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
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

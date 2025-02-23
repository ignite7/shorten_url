<?php

declare(strict_types=1);

namespace App\Validations;

use App\Contracts\ValidationContract;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

final readonly class EmailValidation implements ValidationContract
{
    /**
     * @return array<string, list<Unique|string>>
     */
    public static function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique(User::class, 'email')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function validationAttributes(): array
    {
        return [
            'email' => 'email address',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Validations;

use App\Contracts\ValidationContract;

final readonly class SourceValidation implements ValidationContract
{
    /**
     * @return array<string, list<string>>
     */
    public static function rules(): array
    {
        return [
            'source' => ['required', 'url', 'min:10', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function validationAttributes(): array
    {
        return [
            'source' => 'destination URL',
        ];
    }
}

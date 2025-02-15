<?php

declare(strict_types=1);

namespace App\Rules;

use App\Contracts\RuleContract;

final readonly class SourceRule implements RuleContract
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
}

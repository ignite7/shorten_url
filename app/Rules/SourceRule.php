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

    /**
     * @return array<string, string>
     */
    public static function validationMessages(): array
    {
        return [
            'source.required' => 'The destination URL is required.',
            'source.url' => 'The destination URL must be a valid URL.',
            'source.min' => 'The destination URL must be at least :min characters.',
            'source.max' => 'The destination URL must not be greater than :max characters.',
        ];
    }
}

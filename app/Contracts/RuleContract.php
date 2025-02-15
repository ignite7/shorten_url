<?php

declare(strict_types=1);

namespace App\Contracts;

interface RuleContract
{
    /**
     * @return array<string, list<string>>
     */
    public static function rules(): array;
}

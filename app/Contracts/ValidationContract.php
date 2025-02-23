<?php

declare(strict_types=1);

namespace App\Contracts;

interface ValidationContract
{
    /**
     * @return array<string, list<string>>
     */
    public static function rules(): array;

    /**
     * @return array<string, string>
     */
    public static function validationAttributes(): array;
}

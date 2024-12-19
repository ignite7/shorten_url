<?php

namespace App\Traits;

trait EnumUtils
{
    public static function values(): array
    {
        $values = [];

        foreach (static::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}

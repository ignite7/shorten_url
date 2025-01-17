<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * @template TITem
 */
trait EnumUtils
{
    /**
     * @return array<int, TITem>
     */
    public static function values(): array
    {
        $values = [];

        foreach (static::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}

<?php

declare(strict_types=1);

/** @noinspection PhpIncompatibleReturnTypeInspection */

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

trait SelfCastingModel
{
    public static function instance(Model|Authenticatable $model): static
    {
        return $model;
    }

    public static function unsafeInstance(Model|Authenticatable|null $model): ?static
    {
        return $model;
    }
}

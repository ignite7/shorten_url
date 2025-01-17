<?php

declare(strict_types=1);

/** @noinspection PhpIncompatibleReturnTypeInspection */

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
trait SelfCastingModel
{
    /**
     * @param  TModel|Authenticatable  $model
     * @return TModel
     */
    public static function instance(Model|Authenticatable $model): static
    {
        /** @var TModel $model */
        return $model;
    }

    /**
     * @param  TModel|Authenticatable|null  $model
     * @return TModel|null
     */
    public static function unsafeInstance(Model|Authenticatable|null $model): ?static
    {
        /** @var TModel|null $model */
        return $model;
    }
}

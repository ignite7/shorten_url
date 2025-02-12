<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum UrlStatus: string
{
    /**
     * @phpstan-use EnumUtils<string>
     */
    use EnumUtils;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}

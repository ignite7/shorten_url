<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;

enum FlashMessageType: string
{
    /**
     * @phpstan-use EnumUtils<string>
     */
    use EnumUtils;

    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
}

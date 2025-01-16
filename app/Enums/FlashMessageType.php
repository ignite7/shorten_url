<?php

declare(strict_types=1);

namespace App\Enums;

enum FlashMessageType: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
}

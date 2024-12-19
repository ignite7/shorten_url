<?php

namespace App\Enums;

use App\Traits\EnumUtils;

enum UserRole: string
{
    use EnumUtils;

    case ADMIN = 'admin';
    case STAFF = 'staff';
    case REGULAR = 'regular';
}

<?php

namespace App\Helpers;

final class InertiaHelper
{
    public static function indexPage(string $page): string
    {
        return "{$page}/index";
    }
}

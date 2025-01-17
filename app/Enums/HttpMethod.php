<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumUtils;
use Symfony\Component\HttpFoundation\Request;

enum HttpMethod: string
{
    /**
     * @phpstan-use EnumUtils<string>
     */
    use EnumUtils;

    case HEAD = Request::METHOD_HEAD;
    case GET = Request::METHOD_GET;
    case POST = Request::METHOD_POST;
    case PUT = Request::METHOD_PUT;
    case PATCH = Request::METHOD_PATCH;
    case DELETE = Request::METHOD_DELETE;
    case PURGE = Request::METHOD_PURGE;
    case OPTIONS = Request::METHOD_OPTIONS;
    case TRACE = Request::METHOD_TRACE;
    case CONNECT = Request::METHOD_CONNECT;
}

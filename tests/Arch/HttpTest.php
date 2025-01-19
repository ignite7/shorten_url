<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->not->toBeUsed()
    ->toHaveSuffix('Controller');

arch('middleware')
    ->expect('App\Http\Middleware')
    ->toHaveMethod('handle')
    ->toUse(Request::class)
    ->toBeUsedIn([
        'App\Actions',
    ]);

arch('requests')
    ->expect('App\Http\Requests')
    ->toExtend(FormRequest::class)
    ->toHaveMethod('rules')
    ->toHaveSuffix('Request');

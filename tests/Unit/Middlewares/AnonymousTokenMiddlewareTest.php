<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Http\Middleware\AnonymousTokenMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    Route::middleware(AnonymousTokenMiddleware::class)->get('/test', fn () => response('OK', Response::HTTP_OK));
});

it('generates an anonymous token if the user is not authenticated', function (): void {
    $response = $this->get('/test');

    $response->assertOk();
    $response->assertCookie(CookieKey::ANON_TOKEN->value);
});

it('does not generate an anonymous token if the user is authenticated', function (): void {
    $response = $this->actingAs(User::factory()->create())->get('/test');

    $response->assertOk();
    $response->assertCookieMissing(CookieKey::ANON_TOKEN->value);
});

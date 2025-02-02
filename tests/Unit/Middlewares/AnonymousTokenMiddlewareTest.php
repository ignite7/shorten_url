<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Http\Middleware\AnonymousTokenMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    Route::middleware(AnonymousTokenMiddleware::class)->get('/test', fn () => response('HTTP_FOUND', Response::HTTP_FOUND));
});

it('issues a new anonymous token if not present', function (): void {
    $this->get('/test');

    $queuedCookie = Cookie::queued(CookieKey::ANONYMOUS_TOKEN->value);
    expect($queuedCookie)->not->toBeNull()
        ->and(Str::isUuid($queuedCookie?->getValue()))->toBeTrue();
});

it('does not issue an anonymous token to authenticated users', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/test');

    $response->assertCookieMissing(CookieKey::ANONYMOUS_TOKEN->value);
});

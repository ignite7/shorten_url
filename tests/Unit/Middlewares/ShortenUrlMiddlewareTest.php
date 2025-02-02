<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Helpers\FlashHelper;
use App\Http\Middleware\ShortenUrlMiddleware;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    Route::middleware(ShortenUrlMiddleware::class)->post('/test', fn () => response('HTTP_FOUND', Response::HTTP_FOUND));
});

it('rejects requests without an IP address', function (): void {
    $response = $this->withServerVariables(['REMOTE_ADDR' => null])
        ->post('/test');

    $response->assertRedirect();
    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Unable to determine your IP address.');
});

it('rejects requests without an anonymous token', function (): void {
    $response = $this->post('/test');

    $response->assertRedirect();
    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Unable to determine your anonymous token.');
});

it('rejects requests with an invalid anonymous token', function (): void {
    $response = $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, 'invalid-token')
        ->post('/test');

    $response->assertRedirect();
    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Unable to determine your anonymous token.');
});

it('allows requests with a valid anonymous token', function (): void {
    $validToken = Str::uuid()->toString();

    $response = $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $validToken)
        ->post('/test');

    $response->assertRedirect();
});

it('enforces rate limiting per IP', function (): void {
    $validToken = Str::uuid()->toString();
    $ip = '192.168.1.100';

    // Simulate 5 requests from the same IP
    Request::factory(5)->create(['ip_address' => $ip]);

    // 6th request should be blocked
    $response = $this->withUnencryptedCookie(CookieKey::ANONYMOUS_TOKEN->value, $validToken)
        ->withServerVariables(['REMOTE_ADDR' => $ip])
        ->post('/test');

    $response->assertRedirect();
    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe('You have reached the maximum number of requests allowed per day.');
});

it('does not enforce rate limiting per IP if the user is authenticated', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/test');

    $response->assertRedirect();
});

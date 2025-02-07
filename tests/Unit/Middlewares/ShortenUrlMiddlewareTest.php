<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Enums\HttpMethod;
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

it('enforces rate limiting per IP, method and uri', function (): void {
    $validToken = Str::uuid()->toString();
    $ip = '192.168.1.100';

    // Simulate 5 requests from the same IP
    Request::factory(5)->create([
        'method' => HttpMethod::POST->value,
        'uri' => route('urls.store'),
        'ip_address' => $ip
    ]);

    // 6th request should be blocked
    $response = $this->withUnencryptedCookie(CookieKey::ANONYMOUS_TOKEN->value, $validToken)
        ->withServerVariables(['REMOTE_ADDR' => $ip])
        ->post('/test');

    $response->assertRedirect();
    expect(Session::get(FlashHelper::MESSAGE_KEY))->toBe('You have reached the maximum number of requests allowed per day.');
});

it('only limits requests based on matching method and uri', function (): void {
    $validToken = Str::uuid()->toString();
    $ip = '192.168.1.103';

    // Create a request record with the correct URI but wrong method (GET)
    Request::factory()->create([
        'ip_address' => $ip,
        'method' => HttpMethod::GET->value,  // not POST
        'uri' => route('urls.store'),
    ]);

    // Create a request record with the correct method but a different URI
    Request::factory()->create([
        'ip_address' => $ip,
        'method' => HttpMethod::POST->value,
        'uri' => '/different-uri',
    ]);

    // Create 4 valid requests (POST and matching store URI) that should be counted
    Request::factory(4)->create([
        'ip_address' => $ip,
        'method' => HttpMethod::POST->value,
        'uri' => route('urls.store'),
    ]);

    // At this point, the rate limit check should see only 4 matching requests for this IP.
    // Sending a new POST request to the store route should be allowed.
    $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $validToken)
        ->withServerVariables(['REMOTE_ADDR' => $ip])
        ->post('/test')
        ->assertRedirect()
        ->assertStatus(Response::HTTP_FOUND);
});

it('does not enforce rate limiting per IP if the user is authenticated', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/test');

    $response->assertRedirect();
});

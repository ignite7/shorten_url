<?php

declare(strict_types=1);

use App\Actions\Urls\UpdateAnonymousToken;
use App\Enums\CookieKey;
use Illuminate\Support\Facades\Cookie;

beforeEach(function (): void {
    $this->route = route('urls.anonymous-token.update');
});

describe('UpdateAnonymousToken action', function (): void {
    it('updates the anonymous token cookie', function (): void {
        $anonymousToken = fake()->uuid();

        $this->put($this->route, ['anonymous_token' => $anonymousToken])
            ->assertRedirect();

        $this->assertEquals($anonymousToken, Cookie::queued(CookieKey::ANONYMOUS_TOKEN->value)?->getValue());
    });

    it('validates that anonymous_token is required', function (): void {
        $this->put($this->route)
            ->assertInvalid(['anonymous_token' => 'The anonymous token field is required.']);
    });

    it('validates that anonymous_token is a UUID', function (): void {
        $this->put($this->route, ['anonymous_token' => 'invalid-token'])
            ->assertInvalid(['anonymous_token' => 'The anonymous token field must be a valid UUID.']);
    });
});

describe('can run `UpdateAnonymousToken` action', function (): void {
    test('directly', function (): void {
        $anonymousToken = fake()->uuid();

        UpdateAnonymousToken::run($anonymousToken);

        $this->assertEquals($anonymousToken, Cookie::queued(CookieKey::ANONYMOUS_TOKEN->value)?->getValue());
    });
});

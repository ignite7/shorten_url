<?php

declare(strict_types=1);

use App\Actions\Urls\UpdateAnonymousToken;
use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

beforeEach(function (): void {
    $this->route = route('urls.anonymous-token.update');
});

describe('UpdateAnonymousToken action', function (): void {
    it('updates the anonymous token cookie', function (): void {
        $anonymousToken = fake()->uuid();

        $this->put($this->route, ['anonymous_token' => $anonymousToken])
            ->assertRedirect();

        $this->assertEquals($anonymousToken, Cookie::queued(CookieKey::ANONYMOUS_TOKEN->value)?->getValue());

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Token updated successfully.');
    });

    it('validates that anonymous_token is required', function (): void {
        $this->put($this->route)
            ->assertInvalid(['anonymous_token' => 'The token field is required.']);
    });

    it('validates that anonymous_token is a UUID', function (): void {
        $this->put($this->route, ['anonymous_token' => 'invalid-token'])
            ->assertInvalid(['anonymous_token' => 'The token field must be a valid UUID.']);
    });
});

describe('can run `UpdateAnonymousToken` action', function (): void {
    test('directly', function (): void {
        $anonymousToken = fake()->uuid();

        UpdateAnonymousToken::run($anonymousToken);

        $this->assertEquals($anonymousToken, Cookie::queued(CookieKey::ANONYMOUS_TOKEN->value)?->getValue());
    });
});

it('has rules', function (): void {
    $action = new UpdateAnonymousToken();

    expect($action->rules())->toBeArray()
        ->and($action->rules())->toBe([
            'anonymous_token' => ['required', 'uuid'],
        ]);
});

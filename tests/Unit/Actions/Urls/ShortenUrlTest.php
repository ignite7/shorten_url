<?php

declare(strict_types=1);

use App\Actions\Urls\ShortenUrl;
use App\Enums\FlashMessageType;
use App\Enums\HttpMethod;
use App\Helpers\FlashHelper;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

beforeEach(function (): void {
    $this->route = route('urls.store', absolute: false);
    $this->user = User::factory()->regularRole()->create();
});

describe('shorten a URL', function (): void {
    describe('user', function (): void {
        it('can shorten a URL', function (): void {
            $source = fake()->url();

            $this->actingAs($this->user)
                ->post($this->route, ['source' => $source])
                ->assertRedirect();

            expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
                ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('URL created successfully!');

            $this->assertDatabaseHas(Url::class, [
                'user_id' => $this->user->id,
                'source' => $source,
            ]);

            $this->assertDatabaseHas(Request::class, [
                'url_id' => Url::query()->first()?->id,
                'user_id' => $this->user->id,
            ]);
        });
    });

    describe('guest', function (): void {
        it('can shorten a URL', function (): void {
            $source = fake()->url();

            $this->post($this->route, ['source' => $source])
                ->assertRedirect();

            expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
                ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('URL created successfully!');

            $this->assertDatabaseHas(Url::class, [
                'user_id' => null,
                'source' => $source,
            ]);

            $this->assertDatabaseHas(Request::class, [
                'url_id' => Url::query()->first()?->id,
                'user_id' => null,
            ]);
        });
    });
});

describe('cannot shorten a URL if rules are not met', function (): void {
    it('must be required', function (): void {
        $this->actingAs($this->user)
            ->post($this->route)
            ->assertInvalid(['source' => 'The source field is required.']);

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });

    it('must be a valid URL', function (): void {
        $this->actingAs($this->user)
            ->post($this->route, ['source' => 'invalid-url'])
            ->assertInvalid(['source' => 'The source field must be a valid URL.']);

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });

    it('must have at least 10 characters', function (): void {
        $this->actingAs($this->user)
            ->post($this->route, ['source' => 'short.c'])
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must be at least 10 characters.',
                ],
            ]);

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });

    it('must have a maximum of 255 characters', function (): void {
        $this->actingAs($this->user)
            ->post($this->route, ['source' => Str::random(256)])
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must not be greater than 255 characters.',
                ],
            ]);

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });
});

describe('cannot shorten a URL', function (): void {
    it('cannot shorten a URL if the user role is admin', function (): void {
        $admin = User::factory()->adminRole()->create();

        $this->actingAs($admin)
            ->post($this->route, ['source' => fake()->url()])
            ->assertUnauthorized();

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });

    it('cannot shorten a URL if the user role is staff', function (): void {
        $staff = User::factory()->staffRole()->create();

        $this->actingAs($staff)
            ->post($this->route, ['source' => fake()->url()])
            ->assertUnauthorized();

        $this->assertDatabaseCount(Url::class, 0);
        $this->assertDatabaseCount(Request::class, 0);
    });
});

describe('can run `ShortenUrl` action', function (): void {
    test('admin', function (): void {
        $admin = User::factory()->adminRole()->create();
        $source = fake()->url();

        $request = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => $source]
        );

        $request->setUserResolver(fn () => $admin);

        $shortenUrl = Url::instance(ShortenUrl::run($request));
        $shortenUrlRequest = $shortenUrl->requests->first();

        $this->assertInstanceOf(Url::class, $shortenUrl);
        $this->assertInstanceOf(Request::class, $shortenUrlRequest);
        $this->assertEquals($source, $shortenUrl->source);

        expect($shortenUrl->user_id)->toBe($admin->id)
            ->and($shortenUrlRequest->user_id)->toBe($admin->id)
            ->and($shortenUrlRequest->url_id)->toBe($shortenUrl->id);
    });

    test('staff', function (): void {
        $staff = User::factory()->adminRole()->create();
        $source = fake()->url();

        $request = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => $source]
        );

        $request->setUserResolver(fn () => $staff);

        $shortenUrl = Url::instance(ShortenUrl::run($request));
        $shortenUrlRequest = $shortenUrl->requests->first();

        $this->assertInstanceOf(Url::class, $shortenUrl);
        $this->assertInstanceOf(Request::class, $shortenUrlRequest);
        $this->assertEquals($source, $shortenUrl->source);

        expect($shortenUrl->user_id)->toBe($staff->id)
            ->and($shortenUrlRequest->user_id)->toBe($staff->id)
            ->and($shortenUrlRequest->url_id)->toBe($shortenUrl->id);
    });

    test('regular', function (): void {
        $regular = User::factory()->regularRole()->create();
        $source = fake()->url();

        $request = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => $source]
        );

        $request->setUserResolver(fn () => $regular);

        $shortenUrl = Url::instance(ShortenUrl::run($request));
        $shortenUrlRequest = $shortenUrl->requests->first();

        $this->assertInstanceOf(Url::class, $shortenUrl);
        $this->assertInstanceOf(Request::class, $shortenUrlRequest);
        $this->assertEquals($source, $shortenUrl->source);

        expect($shortenUrl->user_id)->toBe($regular->id)
            ->and($shortenUrlRequest->user_id)->toBe($regular->id)
            ->and($shortenUrlRequest->url_id)->toBe($shortenUrl->id);
    });

    test('guest', function (): void {
        $source = fake()->url();

        $request = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => $source]
        );

        $shortenUrl = Url::instance(ShortenUrl::run($request));
        $shortenUrlRequest = $shortenUrl->requests->first();

        $this->assertInstanceOf(Url::class, $shortenUrl);
        $this->assertInstanceOf(Request::class, $shortenUrlRequest);
        $this->assertEquals($source, $shortenUrl->source);

        expect($shortenUrl->user_id)->toBeNull()
            ->and($shortenUrlRequest->user_id)->toBeNull()
            ->and($shortenUrlRequest->url_id)->toBe($shortenUrl->id);
    });
});

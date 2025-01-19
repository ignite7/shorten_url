<?php

declare(strict_types=1);

use App\Actions\Urls\StoreRequest;
use App\Enums\HttpMethod;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Lorisleiva\Actions\ActionRequest;

beforeEach(function (): void {
    $this->route = route('urls.store', absolute: false);
});

describe('can create request', function (): void {
    it('with user', function (): void {
        $user = User::factory()->create();
        $url = Url::factory()->for($user)->create();
        $actionRequest = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => fake()->url()],
        );

        $actionRequest->setUserResolver(fn () => $user);

        $request = StoreRequest::run($actionRequest, $url->id, $user->id);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertDatabaseCount(Request::class, 1);

        expect($request->url_id)->toEqual($url->id)
            ->and($request->user_id)->toEqual($user->id)
            ->and($request->method)->toEqual($actionRequest->method())
            ->and($request->uri)->toEqual($actionRequest->fullUrl())
            ->and($request->query)->toEqual(collect($actionRequest->query->all()))
            ->and($request->headers)->toEqual(collect($actionRequest->headers->all()))
            ->and($request->body)->toEqual(collect($actionRequest->all()))
            ->and($request->ip_address)->toEqual($actionRequest->ip())
            ->and($request->user_agent)->toEqual($actionRequest->userAgent());
    });

    it('without user', function (): void {
        $url = Url::factory()->create();
        $actionRequest = ActionRequest::create(
            $this->route,
            HttpMethod::POST->value,
            ['source' => fake()->url()],
        );

        $request = StoreRequest::run($actionRequest, $url->id);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertDatabaseCount(Request::class, 1);

        expect($request->url_id)->toEqual($url->id)
            ->and($request->user_id)->toBeNull()
            ->and($request->method)->toEqual($actionRequest->method())
            ->and($request->uri)->toEqual($actionRequest->fullUrl())
            ->and($request->query)->toEqual(collect($actionRequest->query->all()))
            ->and($request->headers)->toEqual(collect($actionRequest->headers->all()))
            ->and($request->body)->toEqual(collect($actionRequest->all()))
            ->and($request->ip_address)->toEqual($actionRequest->ip())
            ->and($request->user_agent)->toEqual($actionRequest->userAgent());
    });
});

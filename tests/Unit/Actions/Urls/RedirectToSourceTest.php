<?php

declare(strict_types=1);

use App\Actions\Urls\RedirectToSource;
use App\Enums\CookieKey;
use App\Enums\HttpMethod;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;

describe('user', function (): void {
    it('redirects to the source URL', function (): void {
        $user = User::factory()->create();
        $url = Url::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('redirect-to-source', ['url' => $url->id]))
            ->assertRedirect($url->source);
    });

    it('stores the request before redirecting', function (): void {
        $user = User::factory()->create();
        $url = Url::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('redirect-to-source', ['url' => $url->id]))
            ->assertRedirect($url->source);

        $this->assertDatabaseCount(Request::class, 1);
        $this->assertDatabaseHas(Request::class, [
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url->id]),
            'url_id' => $url->id,
            'user_id' => $user->id,
        ]);
    });
});

describe('guest', function (): void {
    it('redirects to the source URL', function (): void {
        $url = Url::factory()->withoutUser()->create();

        $this->get(route('redirect-to-source', ['url' => $url->id]))
            ->assertRedirect($url->source);
    });

    it('stores the request before redirecting', function (): void {
        $url = Url::factory()->withoutUser()->create();

        $anonymousToken = Str::uuid()->toString();
        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->get(route('redirect-to-source', ['url' => $url->id]))
            ->assertRedirect($url->source);

        $this->assertDatabaseCount(Request::class, 1);
        $this->assertDatabaseHas(Request::class, [
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url->id]),
            'url_id' => $url->id,
            'user_id' => null,
            'anonymous_token' => $anonymousToken,
        ]);
    });
});

describe('action', function (): void {
    it('redirects to the source URL - guest', function (): void {
        $url = Url::factory()->withoutUser()->create();

        $request = ActionRequest::create(
            route('redirect-to-source', ['url' => $url->id]),
            HttpMethod::GET->value
        );

        $redirect = RedirectToSource::run($request, $url);

        expect($redirect)->toBeInstanceOf(RedirectResponse::class)
            ->and($redirect->getTargetUrl())->toBe($url->source);

        $this->assertDatabaseCount(Request::class, 1);
        $this->assertDatabaseHas(Request::class, [
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url->id]),
            'url_id' => $url->id,
            'user_id' => null,
        ]);
    });

    it('redirects to the source URL - user', function (): void {
        $user = User::factory()->create();
        $url = Url::factory()->for($user)->create();

        $request = ActionRequest::create(
            route('redirect-to-source', ['url' => $url->id]),
            HttpMethod::GET->value
        );
        $request->setUserResolver(fn () => $user);

        $redirect = RedirectToSource::run($request, $url);

        expect($redirect)->toBeInstanceOf(RedirectResponse::class)
            ->and($redirect->getTargetUrl())->toBe($url->source);

        $this->assertDatabaseCount(Request::class, 1);
        $this->assertDatabaseHas(Request::class, [
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url->id]),
            'url_id' => $url->id,
            'user_id' => $user->id,
        ]);
    });
});

<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Models\Url;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function (): void {
    $this->route = route('home');
});

it('gets empty urls if not user or anonymous token is provided', function (): void {
    $this->get($this->route)
        ->assertOk()
        ->assertInertia(fn (Assert $page): AssertableJson => $page
            ->component('Home/index', false)
            ->has('lastShortenedUrl')
            ->has('anonymousToken')
            ->has('urls', fn (Assert $page): AssertableJson => $page
                ->has('data', 0)
                ->has('links', 4)
                ->has('meta', 8)
                ->has('meta.links', 3)
            )
        );
});

describe('guest', function (): void {
    it('can get urls', function (): void {
        $userUrl = Url::factory()->create();
        $url = Url::factory()->withoutUser()->create();

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 1)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $url->id)
                    ->where('data.0.source', $url->source)
                    ->where('data.0.created_at', $url->created_at->toISOString())
                    ->whereNot('data.0.id', $userUrl->id)
                    ->whereNot('data.0.source', $userUrl->source)
                )
            );
    });

    it('can get urls filter by order desc by default', function (): void {
        $url = Url::factory()->withoutUser()->create([
            'created_at' => now()->subDay(),
        ]);
        $url2 = Url::factory()->withoutUser()->create([
            'anonymous_token' => $url->anonymous_token,
        ]);
        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 2)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $url2->id)
                    ->where('data.1.id', $url->id)
                )
            );
    });

    it('can get urls filter by order asc', function (): void {
        $url = Url::factory()->withoutUser()->create([
            'created_at' => now()->subDay(),
        ]);
        $url2 = Url::factory()->withoutUser()->create([
            'anonymous_token' => $url->anonymous_token,
        ]);
        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->get("$this->route?order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 2)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $url->id)
                    ->where('data.1.id', $url2->id)
                )
            );
    });
});

describe('user', function (): void {
    it('can get urls', function (): void {
        $user = User::factory()->regularRole()->create();
        $userUrl = Url::factory()->for($user)->create();
        $userUrl2 = Url::factory()->for(User::factory()->regularRole()->create())->create();
        $url = Url::factory()->withoutUser()->create();

        $this->actingAs($user)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 1)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $userUrl->id)
                    ->where('data.0.source', $userUrl->source)
                    ->where('data.0.created_at', $userUrl->created_at->toISOString())
                    ->whereNot('data.0.id', $url->id)
                    ->whereNot('data.0.source', $url->source)
                    ->whereNot('data.0.id', $userUrl2->id)
                    ->whereNot('data.0.source', $userUrl2->source)
                )
            );
    });

    it('can get urls filter by order desc by default', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create([
            'created_at' => now()->subDay(),
        ]);
        $url2 = Url::factory()->for($user)->create();
        $this->actingAs($user)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 2)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $url2->id)
                    ->where('data.1.id', $url->id)
                )
            );
    });

    it('can get urls filter by order asc', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create([
            'created_at' => now()->subDay(),
        ]);
        $url2 = Url::factory()->for($user)->create();
        $this->actingAs($user)
            ->get("$this->route?order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index', false)
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
                ->has('urls', fn (Assert $page): AssertableJson => $page
                    ->has('data', 2)
                    ->has('links', 4)
                    ->has('meta', 8)
                    ->has('meta.links', 3)
                    ->where('data.0.id', $url->id)
                    ->where('data.1.id', $url2->id)
                )
            );
    });
});

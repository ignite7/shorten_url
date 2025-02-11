<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Enums\HttpMethod;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function (): void {
    $this->route = route('home');
});

it('gets empty urls if no user or anonymous token is provided', function (): void {
    $this->get($this->route)
        ->assertOk()
        ->assertInertia(fn (Assert $page): AssertableJson => $page
            ->component('Home/index')
            ->has('lastShortenedUrl')
            ->has('anonymousToken')
            ->has('urls.data', 0)
        );
});

describe('guest', function (): void {
    it('can get urls ordered by clicks in descending order', function (): void {
        $anonymousToken = fake()->uuid();
        $url1 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);
        $url2 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);
        $url3 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);

        Request::factory(3)->create(['url_id' => $url1->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url1->id])]);
        Request::factory(5)->create(['url_id' => $url2->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url2->id])]);
        Request::factory(1)->create(['url_id' => $url3->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url3->id])]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->get("$this->route?orderBy=clicks&order=desc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url2->id)
                ->where('urls.data.1.id', $url1->id)
                ->where('urls.data.2.id', $url3->id)
            );
    });

    it('can get urls ordered by clicks in ascending order', function (): void {
        $anonymousToken = fake()->uuid();
        $url1 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);
        $url2 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);
        $url3 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);

        Request::factory(3)->create([
            'url_id' => $url1->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url1->id]),
        ]);
        Request::factory(5)->create([
            'url_id' => $url2->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url2->id]),
        ]);
        Request::factory()->create([
            'url_id' => $url3->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url3->id]),
        ]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->get("$this->route?orderBy=clicks&order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url3->id) // Lowest clicks
                ->where('urls.data.1.id', $url1->id)
                ->where('urls.data.2.id', $url2->id) // Highest clicks
            );
    });

    it('can get urls ordered by created_at in descending order (default)', function (): void {
        $anonymousToken = fake()->uuid();
        $url1 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken, 'created_at' => now()->subDays(2)]);
        $url2 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken, 'created_at' => now()->subDay()]);
        $url3 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url3->id)
                ->where('urls.data.1.id', $url2->id)
                ->where('urls.data.2.id', $url1->id)
            );
    });

    it('can get urls ordered by created_at in ascending order', function (): void {
        $anonymousToken = fake()->uuid();
        $url1 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken, 'created_at' => now()->subDays(2)]);
        $url2 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken, 'created_at' => now()->subDay()]);
        $url3 = Url::factory()->withoutUser()->create(['anonymous_token' => $anonymousToken]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken)
            ->get("$this->route?order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url1->id)
                ->where('urls.data.1.id', $url2->id)
                ->where('urls.data.2.id', $url3->id)
            );
    });
});

describe('user', function (): void {
    it('can get urls ordered by clicks in descending order', function (): void {
        $user = User::factory()->regularRole()->create();
        $url1 = Url::factory()->for($user)->create();
        $url2 = Url::factory()->for($user)->create();
        $url3 = Url::factory()->for($user)->create();

        Request::factory(2)->create(['url_id' => $url1->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url1->id])]);
        Request::factory(4)->create(['url_id' => $url2->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url2->id])]);
        Request::factory(1)->create(['url_id' => $url3->id, 'method' => HttpMethod::GET->value, 'uri' => route('redirect-to-source', ['url' => $url3->id])]);

        $this->actingAs($user)
            ->get("$this->route?orderBy=clicks&order=desc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url2->id)
                ->where('urls.data.1.id', $url1->id)
                ->where('urls.data.2.id', $url3->id)
            );
    });

    it('can get urls ordered by clicks in ascending order', function (): void {
        $user = User::factory()->regularRole()->create();
        $url1 = Url::factory()->for($user)->create();
        $url2 = Url::factory()->for($user)->create();
        $url3 = Url::factory()->for($user)->create();

        Request::factory(2)->create([
            'url_id' => $url1->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url1->id]),
        ]);
        Request::factory(4)->create([
            'url_id' => $url2->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url2->id]),
        ]);
        Request::factory(1)->create([
            'url_id' => $url3->id,
            'method' => HttpMethod::GET->value,
            'uri' => route('redirect-to-source', ['url' => $url3->id]),
        ]);

        $this->actingAs($user)
            ->get("$this->route?orderBy=clicks&order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url3->id) // Lowest clicks
                ->where('urls.data.1.id', $url1->id)
                ->where('urls.data.2.id', $url2->id) // Highest clicks
            );
    });

    it('can get urls ordered by created_at in descending order (default)', function (): void {
        $user = User::factory()->regularRole()->create();
        $url1 = Url::factory()->for($user)->create(['created_at' => now()->subDays(2)]);
        $url2 = Url::factory()->for($user)->create(['created_at' => now()->subDay()]);
        $url3 = Url::factory()->for($user)->create();

        $this->actingAs($user)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url3->id)
                ->where('urls.data.1.id', $url2->id)
                ->where('urls.data.2.id', $url1->id)
            );
    });

    it('can get urls ordered by created_at in ascending order', function (): void {
        $user = User::factory()->regularRole()->create();
        $url1 = Url::factory()->for($user)->create(['created_at' => now()->subDays(2)]);
        $url2 = Url::factory()->for($user)->create(['created_at' => now()->subDay()]);
        $url3 = Url::factory()->for($user)->create();

        $this->actingAs($user)
            ->get("$this->route?order=asc")
            ->assertOk()
            ->assertInertia(fn (Assert $page): AssertableJson => $page
                ->component('Home/index')
                ->where('urls.data.0.id', $url1->id)
                ->where('urls.data.1.id', $url2->id)
                ->where('urls.data.2.id', $url3->id)
            );
    });
});

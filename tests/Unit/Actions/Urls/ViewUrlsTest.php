<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Models\Url;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function (): void {
    $this->route = route('home');
});

it('gets empty urls if not user or anonymous token is provided', function (): void {
    $response = $this->get($this->route)
        ->assertOk()
        ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
            ->component('Home/index')
            ->has('lastShortenedUrl')
            ->has('anonymousToken')
        );
    $data = $response->original->getData()['page'];
    expect($data['deferredProps']['default'])->toContain('urls');

    $this->withHeaders([
        'X-Inertia' => true,
        'X-Inertia-Partial-Component' => 'Home/index',
        'X-Inertia-Partial-Data' => 'urls',
        'X-Inertia-Version' => $data['version'],
    ])
        ->get($this->route)
        ->assertOk()
        ->assertJsonCount(0, 'props.urls.data')
        ->assertJsonCount(4, 'props.urls.links')
        ->assertJsonCount(8, 'props.urls.meta')
        ->assertJsonCount(3, 'props.urls.meta.links');
});

describe('guest', function (): void {
    it('can get urls', function (): void {
        $userUrl = Url::factory()->create();
        $url = Url::factory()->withoutUser()->create();

        $response = $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('Home/index')
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
            );
        $data = $response->original->getData()['page'];
        expect($data['deferredProps']['default'])->toContain('urls');

        $this
            ->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->withHeaders([
                'X-Inertia' => true,
                'X-Inertia-Partial-Component' => 'Home/index',
                'X-Inertia-Partial-Data' => 'urls',
                'X-Inertia-Version' => $data['version'],
            ])
            ->get($this->route)
            ->assertOk()
            ->assertJsonCount(1, 'props.urls.data')
            ->assertJsonCount(4, 'props.urls.links')
            ->assertJsonCount(8, 'props.urls.meta')
            ->assertJsonCount(3, 'props.urls.meta.links')
            ->assertJsonStructure([
                'props' => [
                    'urls' => [
                        'data' => [
                            '*' => [
                                'id',
                                'source',
                                'created_at',
                            ],
                        ],
                        'links' => [
                            'first',
                            'last',
                            'prev',
                            'next',
                        ],
                        'meta' => [
                            'current_page',
                            'from',
                            'last_page',
                            'links' => [
                                '*' => [
                                    'url',
                                    'label',
                                    'active',
                                ],
                            ],
                            'path',
                            'per_page',
                            'to',
                            'total',
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment([
                'id' => $url->id,
                'source' => $url->source,
                'created_at' => $url->created_at->toISOString(),
            ])
            ->assertJsonMissing([
                'id' => $userUrl->id,
                'source' => $userUrl->source,
            ]);
    });

    it('can get urls filter by order desc by default',
        /**
         * @throws JsonException
         */
        function (): void {
            $url = Url::factory()->withoutUser()->create([
                'created_at' => now()->subDay(),
            ]);
            $url2 = Url::factory()->withoutUser()->create([
                'anonymous_token' => $url->anonymous_token,
            ]);
            $response = $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
                ->get($this->route)
                ->assertOk()
                ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                    ->component('Home/index')
                    ->has('lastShortenedUrl')
                    ->has('anonymousToken')
                );
            $data = $response->original->getData()['page'];
            expect($data['deferredProps']['default'])->toContain('urls');

            $response2 = $this
                ->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
                ->withHeaders([
                    'X-Inertia' => true,
                    'X-Inertia-Partial-Component' => 'Home/index',
                    'X-Inertia-Partial-Data' => 'urls',
                    'X-Inertia-Version' => $data['version'],
                ])
                ->get($this->route)
                ->assertOk()
                ->assertJsonCount(2, 'props.urls.data')
                ->assertJsonCount(4, 'props.urls.links')
                ->assertJsonCount(8, 'props.urls.meta')
                ->assertJsonCount(3, 'props.urls.meta.links');

            $data2 = json_decode((string) $response2->content(), true, 512, JSON_THROW_ON_ERROR);
            expect($data2['props']['urls']['data'][0]['id'])->toBe($url2->id)
                ->and($data2['props']['urls']['data'][1]['id'])->toBe($url->id);
        });

    it('can get urls filter by order asc',
        /**
         * @throws JsonException
         */
        function (): void {
            $url = Url::factory()->withoutUser()->create([
                'created_at' => now()->subDay(),
            ]);
            $url2 = Url::factory()->withoutUser()->create([
                'anonymous_token' => $url->anonymous_token,
            ]);
            $response = $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
                ->get("{$this->route}?order=asc")
                ->assertOk()
                ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                    ->component('Home/index')
                    ->has('lastShortenedUrl')
                    ->has('anonymousToken')
                );
            $data = $response->original->getData()['page'];
            expect($data['deferredProps']['default'])->toContain('urls');

            $response2 = $this
                ->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
                ->withHeaders([
                    'X-Inertia' => true,
                    'X-Inertia-Partial-Component' => 'Home/index',
                    'X-Inertia-Partial-Data' => 'urls',
                    'X-Inertia-Version' => $data['version'],
                ])
                ->get("{$this->route}?order=asc")
                ->assertOk()
                ->assertJsonCount(2, 'props.urls.data')
                ->assertJsonCount(4, 'props.urls.links')
                ->assertJsonCount(8, 'props.urls.meta')
                ->assertJsonCount(3, 'props.urls.meta.links');

            $data2 = json_decode((string) $response2->content(), true, 512, JSON_THROW_ON_ERROR);
            expect($data2['props']['urls']['data'][0]['id'])->toBe($url->id)
                ->and($data2['props']['urls']['data'][1]['id'])->toBe($url2->id);
        });
});

describe('user', function (): void {
    it('can get urls', function (): void {
        $user = User::factory()->regularRole()->create();
        $userUrl = Url::factory()->for($user)->create();
        $userUrl2 = Url::factory()->for(User::factory()->regularRole()->create())->create();
        $url = Url::factory()->withoutUser()->create();

        $response = $this->actingAs($user)
            ->get($this->route)
            ->assertOk()
            ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                ->component('Home/index')
                ->has('lastShortenedUrl')
                ->has('anonymousToken')
            );
        $data = $response->original->getData()['page'];
        expect($data['deferredProps']['default'])->toContain('urls');

        $this->actingAs($user)
            ->withHeaders([
                'X-Inertia' => true,
                'X-Inertia-Partial-Component' => 'Home/index',
                'X-Inertia-Partial-Data' => 'urls',
                'X-Inertia-Version' => $data['version'],
            ])
            ->get($this->route)
            ->assertOk()
            ->assertJsonCount(1, 'props.urls.data')
            ->assertJsonCount(4, 'props.urls.links')
            ->assertJsonCount(8, 'props.urls.meta')
            ->assertJsonCount(3, 'props.urls.meta.links')
            ->assertJsonStructure([
                'props' => [
                    'urls' => [
                        'data' => [
                            '*' => [
                                'id',
                                'source',
                                'created_at',
                            ],
                        ],
                        'links' => [
                            'first',
                            'last',
                            'prev',
                            'next',
                        ],
                        'meta' => [
                            'current_page',
                            'from',
                            'last_page',
                            'links' => [
                                '*' => [
                                    'url',
                                    'label',
                                    'active',
                                ],
                            ],
                            'path',
                            'per_page',
                            'to',
                            'total',
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment([
                'id' => $userUrl->id,
                'source' => $userUrl->source,
                'created_at' => $userUrl->created_at->toISOString(),
            ])
            ->assertJsonMissing([
                'id' => $url->id,
                'source' => $url->source,
            ])
            ->assertJsonMissing([
                'id' => $userUrl2->id,
                'source' => $userUrl2->source,
            ]);
    });

    it('can get urls filter by order desc by default',
        /**
         * @throws JsonException
         */
        function (): void {
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->for($user)->create([
                'created_at' => now()->subDay(),
            ]);
            $url2 = Url::factory()->for($user)->create();
            $response = $this->actingAs($user)
                ->get($this->route)
                ->assertOk()
                ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                    ->component('Home/index')
                    ->has('lastShortenedUrl')
                    ->has('anonymousToken')
                );
            $data = $response->original->getData()['page'];
            expect($data['deferredProps']['default'])->toContain('urls');

            $response2 = $this->actingAs($user)
                ->withHeaders([
                    'X-Inertia' => true,
                    'X-Inertia-Partial-Component' => 'Home/index',
                    'X-Inertia-Partial-Data' => 'urls',
                    'X-Inertia-Version' => $data['version'],
                ])
                ->get($this->route)
                ->assertOk()
                ->assertJsonCount(2, 'props.urls.data')
                ->assertJsonCount(4, 'props.urls.links')
                ->assertJsonCount(8, 'props.urls.meta')
                ->assertJsonCount(3, 'props.urls.meta.links');

            $data2 = json_decode((string) $response2->content(), true, 512, JSON_THROW_ON_ERROR);
            expect($data2['props']['urls']['data'][0]['id'])->toBe($url2->id)
                ->and($data2['props']['urls']['data'][1]['id'])->toBe($url->id);
        });

    it('can get urls filter by order asc',
        /**
         * @throws JsonException
         */
        function (): void {
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->for($user)->create([
                'created_at' => now()->subDay(),
            ]);
            $url2 = Url::factory()->for($user)->create();
            $response = $this->actingAs($user)
                ->get("{$this->route}?order=asc")
                ->assertOk()
                ->assertInertia(fn (Assert $page): \Illuminate\Testing\Fluent\AssertableJson => $page
                    ->component('Home/index')
                    ->has('lastShortenedUrl')
                    ->has('anonymousToken')
                );
            $data = $response->original->getData()['page'];
            expect($data['deferredProps']['default'])->toContain('urls');

            $response2 = $this->actingAs($user)
                ->withHeaders([
                    'X-Inertia' => true,
                    'X-Inertia-Partial-Component' => 'Home/index',
                    'X-Inertia-Partial-Data' => 'urls',
                    'X-Inertia-Version' => $data['version'],
                ])
                ->get("{$this->route}?order=asc")
                ->assertOk()
                ->assertJsonCount(2, 'props.urls.data')
                ->assertJsonCount(4, 'props.urls.links')
                ->assertJsonCount(8, 'props.urls.meta')
                ->assertJsonCount(3, 'props.urls.meta.links');

            $data2 = json_decode((string) $response2->content(), true, 512, JSON_THROW_ON_ERROR);
            expect($data2['props']['urls']['data'][0]['id'])->toBe($url->id)
                ->and($data2['props']['urls']['data'][1]['id'])->toBe($url2->id);
        });
});

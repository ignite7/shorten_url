<?php

declare(strict_types=1);

use App\Actions\Urls\UpdateUrlSource;
use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Models\Url;
use App\Models\User;
use App\Rules\SourceRule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

describe('regular', function (): void {
    it('can update url source', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = fake()->url();

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The original link has been updated.');

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);
    });

    it('cannot update url source if the source field was not provided', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = fake()->url();

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]))
            ->assertRedirect()
            ->assertInvalid([
                'source' => 'The source field is required.',
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is not a valid url', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = 'invalid-url';

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => 'The source field must be a valid URL.',
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is not at least 10 characters', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = 'short.c';

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must be at least 10 characters.',
                ],
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is more than 255 characters', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = Str::random(256);

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must not be greater than 255 characters.',
                ],
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field was not changed', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();
        $source = fake()->url();

        // Prevent updates on the model
        Url::saving(fn (): false => false);

        $this->actingAs($user)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The original link could not be updated.');

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });
});

describe('guest', function (): void {
    it('can update url source', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = fake()->url();

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The original link has been updated.');

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);
    });

    it('cannot update url source if the source field was not provided', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = fake()->url();

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]))
            ->assertRedirect()
            ->assertInvalid([
                'source' => 'The source field is required.',
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is not a valid url', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = 'invalid-url';

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => 'The source field must be a valid URL.',
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is not at least 10 characters', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = 'short.c';

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must be at least 10 characters.',
                ],
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field is more than 255 characters', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = Str::random(256);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect()
            ->assertInvalid([
                'source' => [
                    'The source field must be a valid URL.',
                    'The source field must not be greater than 255 characters.',
                ],
            ]);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });

    it('cannot update url source if the source field was not changed', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $source = fake()->url();

        // Prevent updates on the model
        Url::saving(fn (): false => false);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.source.update', ['url' => $url->id]), [
                'source' => $source,
            ])
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The original link could not be updated.');

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $url->source,
        ]);
    });
});

describe('can handle action', function (): void {
    it('can update url source', function (): void {
        $oldSource = fake()->url();
        $url = Url::factory()->create([
            'source' => $oldSource,
        ]);
        $source = fake()->url();

        UpdateUrlSource::run($url, $source);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'source' => $oldSource,
        ]);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'source' => $source,
        ]);
    });
});

it('has middlewares', function (): void {
    $action = new UpdateUrlSource();

    expect($action->getControllerMiddleware())->toBeArray()
        ->and($action->getControllerMiddleware())->toBe(['can:update,url']);
});

it('has rules', function (): void {
    $action = new UpdateUrlSource();

    expect($action->rules())->toBeArray()
        ->and($action->rules())->toBe(SourceRule::rules());
});

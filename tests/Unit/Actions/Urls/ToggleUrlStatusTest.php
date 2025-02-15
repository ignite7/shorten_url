<?php

declare(strict_types=1);

use App\Actions\Urls\ToggleUrlStatus;
use App\Enums\CookieKey;
use App\Enums\FlashMessageType;
use App\Enums\UrlStatus;
use App\Helpers\FlashHelper;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Session;

describe('regular', function (): void {
    it('can toggle url to inactive status', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create();

        $this->actingAs($user)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status now is inactive.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::INACTIVE->value,
        ]);
    });

    it('can toggle url to active status', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create([
            'status' => UrlStatus::INACTIVE->value,
        ]);

        $this->actingAs($user)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status now is active.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::ACTIVE->value,
        ]);
    });

    it('cannot toggle url if the status field was not changed', function (): void {
        $user = User::factory()->regularRole()->create();
        $url = Url::factory()->for($user)->create([
            'status' => UrlStatus::ACTIVE->value,
        ]);

        // Prevent updates on the model
        Url::saving(fn (): false => false);

        $this->actingAs($user)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status could not be changed.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::ACTIVE->value,
        ]);
    });
});

describe('guest', function (): void {
    it('can toggle url to inactive status', function (): void {
        $url = Url::factory()->withoutUser()->create();

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status now is inactive.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::INACTIVE->value,
        ]);
    });

    it('can toggle url to active status', function (): void {
        $url = Url::factory()->withoutUser()->create([
            'status' => UrlStatus::INACTIVE->value,
        ]);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::SUCCESS->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status now is active.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::ACTIVE->value,
        ]);
    });

    it('cannot toggle url if the status field was not changed', function (): void {
        $url = Url::factory()->withoutUser()->create();

        // Prevent updates on the model
        Url::saving(fn (): false => false);

        $this->withCookie(CookieKey::ANONYMOUS_TOKEN->value, $url->anonymous_token)
            ->put(route('urls.toggle-status', ['url' => $url->id]))
            ->assertRedirect();

        expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
            ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('The url status could not be changed.');

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::ACTIVE->value,
        ]);
    });
});

describe('can handle action', function (): void {
    it('can toggle url to inactive status', function (): void {
        $url = Url::factory()->create();

        ToggleUrlStatus::run($url);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::INACTIVE->value,
        ]);
    });

    it('can toggle url to active status', function (): void {
        $url = Url::factory()->create([
            'status' => UrlStatus::INACTIVE->value,
        ]);

        ToggleUrlStatus::run($url);

        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'status' => UrlStatus::ACTIVE->value,
        ]);
    });
});

it('has middlewares', function (): void {
    $action = new ToggleUrlStatus();

    expect($action->getControllerMiddleware())->toBeArray()
        ->and($action->getControllerMiddleware())->toBe(['can:update,url']);
});

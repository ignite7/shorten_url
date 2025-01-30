<?php

declare(strict_types=1);

use App\Enums\FlashMessageType;
use App\Helpers\FlashHelper;
use App\Models\User;
use Illuminate\Support\Facades\Session;

beforeEach(function (): void {
    $this->route = route('urls.store', absolute: false);
});

it('cannot shorten a URL if the request is missing the IP address', function (): void {
    $this->withHeaders(['REMOTE_ADDR' => null])
        ->post($this->route)
        ->assertRedirect();

    expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
        ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('Unable to determine your IP address.');
});

it('cannot shorten a URL if the user is not authenticated and has made more than 5 requests in a day', function (): void {
    // Simulate 5 valid requests
    for ($i = 0; $i < 5; $i++) {
        $this->post($this->route, ['source' => fake()->url()])
            ->assertRedirect();
    }

    // 6th request should fail
    $this->post($this->route, ['source' => fake()->url()])
        ->assertRedirect();

    expect(Session::get(FlashHelper::MESSAGE_TYPE_KEY))->toBe(FlashMessageType::ERROR->value)
        ->and(Session::get(FlashHelper::MESSAGE_KEY))->toBe('You have reached the maximum number of requests allowed per day.');
});

describe('unlimited requests per day if the user is authenticated', function (): void {
    it('cannot have unlimited requests for admin user', function (): void {
        $user = User::factory()->adminRole()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)
                ->post($this->route, ['source' => fake()->url()])
                ->assertUnauthorized();
        }
    });

    it('cannot have unlimited requests for staff user', function (): void {
        $user = User::factory()->staffRole()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)
                ->post($this->route, ['source' => fake()->url()])
                ->assertUnauthorized();
        }
    });

    it('can have unlimited request for regular user', function (): void {
        $user = User::factory()->regularRole()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)
                ->post($this->route, ['source' => fake()->url()])
                ->assertRedirect();
        }
    });
});

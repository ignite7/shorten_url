<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function () {
    $this->route = route('urls.store', absolute: false);
});

it('cannot shorten a URL if the request is missing the IP address', function (): void {
    $this->withHeaders(['REMOTE_ADDR' => null])
        ->post($this->route)
        ->assertBadRequest();
});

it('cannot shorten a URL if the user is not authenticated and has made more than 5 requests in a day', function (): void {
    // Simulate 5 valid requests
    for ($i = 0; $i < 5; $i++) {
        $this->post($this->route, ['source' => fake()->url()])
            ->assertCreated();
    }

    // 6th request should fail
    $this->post($this->route, ['source' => fake()->url()])
        ->assertTooManyRequests();
});

it('can have more than 5 requests per day if the user is authenticated', function (): void {
    $user = User::factory()->create();

    for ($i = 0; $i < 10; $i++) {
        $this->actingAs($user)
            ->post($this->route, ['source' => fake()->url()])
            ->assertCreated();
    }
});

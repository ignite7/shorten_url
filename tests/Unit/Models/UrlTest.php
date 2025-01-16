<?php

declare(strict_types=1);

use App\Models\Request;
use App\Models\Url;
use App\Models\User;

it('can get the url in an array format', function (): void {
    $url = Url::factory()->create();

    expect($url->toArray())->toMatchArray([
        'source' => $url->source,
        'updated_at' => $url->updated_at?->toISOString(),
        'created_at' => $url->created_at?->toISOString(),
    ]);
});

it('can get the user of the url', function (): void {
    $user = User::factory()->create();
    $url = Url::factory()->create(['user_id' => $user->id]);

    expect($url->user->id)->toBe($user->id);
});

it('cannot get the user of the url if it is nullable', function (): void {
    $url = Url::factory()->withoutUser()->create();

    expect($url->user)->toBeNull();
});

it('can get the requests of the url', function (): void {
    $user = User::factory()->create();
    $url = Url::factory()->for($user)->create();
    $requests = Request::factory(5)->for($url)
        ->for($user)->create();

    expect($url->requests)->toHaveCount(5)
        ->and($requests)->toHaveCount(5)
        ->and($url->requests->pluck('id')->toArray())
        ->toMatchArray($requests->pluck('id')->toArray());
});

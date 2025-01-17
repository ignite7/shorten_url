<?php

use App\Models\Request;
use App\Models\Url;
use App\Models\User;

it('can get the user in an array format', function (): void {
    $request = Request::factory()->create();

    expect($request->toArray())->toMatchArray([
        'method' => $request->method,
        'uri' => $request->uri,
        'query' => $request->query->toArray(),
        'headers' => $request->headers->toArray(),
        'body' => $request->body,
        'ip_address' => $request->ip_address,
        'user_agent' => $request->user_agent,
        'updated_at' => $request->updated_at?->toISOString(),
        'created_at' => $request->created_at?->toISOString(),
    ]);
});

it('can get the url of the request', function (): void {
    $user = User::factory()->create();
    $url = Url::factory()->for($user)->create();
    $request = Request::factory()->for($url)->for($user)->create();

    expect($request->url->id)->toBe($url->id);
});

it('can get the user of the request', function (): void {
    $user = User::factory()->create();
    $url = Url::factory()->for($user)->create();
    $request = Request::factory()->for($url)->for($user)->create();

    expect($request->user->id)->toBe($user->id);
});

it('cannot get the user of the request if the user is nullable', function (): void {
    $request = Request::factory()->withoutUser()->create();

    expect($request->user)->toBeNull();
});

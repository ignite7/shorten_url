<?php

declare(strict_types=1);

use App\Models\Request;
use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can get the user in an array format', function (): void {
    $user = User::factory()->create();

    expect($user->toArray())->toBe([
        'role' => $user->role,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'email_verified_at' => $user->email_verified_at?->toISOString(),
        'created_at' => $user->created_at?->toISOString(),
    ]);
});

it('can hash the password using the setter', function (): void {
    $password = 'password1234!';
    $user = User::factory()->create(['password' => $password]);

    expect($user->password)->not()->toBe($password)
        ->and(Hash::check($password, $user->password))->toBeTrue();
});

it('can get the full name of the user', function (): void {
    $user = User::factory()->create();

    expect($user->fullName)->toBe("$user->first_name $user->last_name");
});

it('can get the urls of the user', function (): void {
    $user = User::factory()->create();
    $urls = Url::factory(5)->for($user)->create();

    expect($user->urls)->toHaveCount(5)
        ->and($urls)->toHaveCount(5)
        ->and($user->urls->pluck('id')->toArray())
        ->toBe($urls->pluck('id')->toArray());
});

it('can get the requests of the user', function (): void {
    $user = User::factory()->create();
    $url = Url::factory()->for($user)->create();
    $requests = Request::factory(5)->for($url)
        ->for($user)->create();

    expect($user->requests)->toHaveCount(5)
        ->and($requests)->toHaveCount(5)
        ->and($user->requests->pluck('id')->toArray())
        ->toBe($requests->pluck('id')->toArray());
});

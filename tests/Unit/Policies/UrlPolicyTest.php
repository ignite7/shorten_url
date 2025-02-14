<?php

declare(strict_types=1);

use App\Enums\CookieKey;
use App\Models\Url;
use App\Models\User;
use App\Policies\UrlPolicy;

describe('admin', function (): void {
    it('cannot create', function (): void {
        $user = User::factory()->adminRole()->create();

        $policy = new UrlPolicy();

        expect($policy->create($user))->toBeFalse();
    });

    it('can update', function (): void {
        $user = User::factory()->adminRole()->create();
        $url = Url::factory()->for($user)->create();

        $policy = new UrlPolicy();

        expect($policy->update($user, $url))->toBeTrue();
    });
});

describe('staff', function (): void {
    it('cannot create', function (): void {
        $user = User::factory()->staffRole()->create();

        $policy = new UrlPolicy();

        expect($policy->create($user))->toBeFalse();
    });

    it('can update', function (): void {
        $user = User::factory()->staffRole()->create();
        $url = Url::factory()->for($user)->create();

        $policy = new UrlPolicy();

        expect($policy->update($user, $url))->toBeTrue();
    });
});

describe('regular', function (): void {
    it('can create', function (): void {
        $user = User::factory()->regularRole()->create();

        $policy = new UrlPolicy();

        expect($policy->create($user))->toBeTrue();
    });

    describe('update', function (): void {
        it('can', function (): void {
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->for($user)->create();

            $policy = new UrlPolicy();

            expect($policy->update($user, $url))->toBeTrue();
        });

        it('cannot update if url belong to another user', function (): void {
            $user = User::factory()->regularRole()->create();
            $owner = User::factory()->regularRole()->create();
            $url = Url::factory()->for($owner)->create();

            $policy = new UrlPolicy();

            expect($policy->update($user, $url))->toBeFalse();
        });

        it('cannot update if url does not have user and has anonymous token', function (): void {
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->withoutUser()->create();

            $policy = new UrlPolicy();

            expect($policy->update($user, $url))->toBeFalse();
        });
    });
});

describe('guest', function (): void {
    it('can create', function (): void {
        $policy = new UrlPolicy();

        expect($policy->create(null))->toBeTrue();
    });

    describe('update', function (): void {
        it('can', function (): void {
            $anonymousToken = fake()->uuid();
            $url = Url::factory()->withoutUser()->create([
                'anonymous_token' => $anonymousToken,
            ]);

            request()->cookies->set(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken);

            $policy = new UrlPolicy();

            expect($policy->update(null, $url))->toBeTrue();
        });

        it('cannot update if belong to user', function (): void {
            $anonymousToken = fake()->uuid();
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->for($user)->create([
                'anonymous_token' => $anonymousToken,
            ]);

            request()->cookies->set(CookieKey::ANONYMOUS_TOKEN->value, $anonymousToken);

            $policy = new UrlPolicy();

            expect($policy->update(null, $url))->toBeFalse();
        });

        it('cannot update if belong to user and does not have anonymous token', function (): void {
            $user = User::factory()->regularRole()->create();
            $url = Url::factory()->for($user)->create();

            $policy = new UrlPolicy();

            expect($policy->update(null, $url))->toBeFalse();
        });
    });
});

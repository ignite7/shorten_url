<?php

declare(strict_types=1);

use App\Models\User;
use App\Policies\UrlPolicy;

describe('create', function (): void {
    describe('admin', function (): void {
        it('cannot create', function (): void {
            $user = User::factory()->adminRole()->create();

            $policy = new UrlPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });

    describe('staff', function (): void {
        it('cannot create', function (): void {
            $user = User::factory()->staffRole()->create();

            $policy = new UrlPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });

    describe('regular', function (): void {
        it('can create', function (): void {
            $user = User::factory()->regularRole()->create();

            $policy = new UrlPolicy();

            expect($policy->create($user))->toBeTrue();
        });
    });
});

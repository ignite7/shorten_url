<?php

use App\Models\User;

it('can cast a model to itself using `instance`', function () {
    $instance = User::instance(new User());

    expect($instance)->toBeInstanceOf(User::class);
});

it('can cast a model to itself using `unsafeInstance`', function () {
    $instance = User::unsafeInstance(new User());

    expect($instance)->toBeInstanceOf(User::class);
});

it('can cast a model to null using `unsafeInstance`', function () {
    $instance = User::unsafeInstance(null);

    expect($instance)->toBeNull();
});

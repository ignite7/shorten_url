<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\CookieKey;
use App\Enums\UserRole;
use App\Models\Url;
use App\Models\User;

final readonly class UrlPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        if (is_null($user)) {
            return true;
        }

        return $user->role === UserRole::REGULAR->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Url $url): bool
    {
        if (is_null($user)) {
            return is_null($url->user_id) && $url->anonymous_token === request()->cookie(CookieKey::ANONYMOUS_TOKEN->value);
        }

        if ($user->role === UserRole::ADMIN->value || $user->role === UserRole::STAFF->value) {
            return true;
        }

        return is_null($url->anonymous_token) && $url->user()->is($user);
    }
}

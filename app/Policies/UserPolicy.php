<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

final readonly class UserPolicy
{
    /**
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->isAdminOrStaff($user);
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        if ($user->is($model)) {
            return true;
        }

        return $this->isAdminOrStaff($user);
    }

    /**
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        // Users can update itself
        if ($user->is($model)) {
            return true;
        }

        // Admins can't update themselves
        if ($user->role === UserRole::ADMIN->value && $model->role === UserRole::ADMIN->value) {
            return false;
        }

        if ($user->role === UserRole::STAFF->value) {
            // Staff can't update themselves
            if ($model->role === UserRole::STAFF->value) {
                return false;
            }

            // Staff can't update admins
            if ($model->role === UserRole::ADMIN->value) {
                return false;
            }
        }

        // Admins and staff can update regular users
        return $this->isAdminOrStaff($user);
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        // Users can delete itself
        if ($user->is($model)) {
            return true;
        }

        if ($user->role === UserRole::ADMIN->value) {
            // Admins can't delete themselves
            if ($model->role === UserRole::ADMIN->value) {
                return false;
            }

            // Admins can delete staff
            if ($model->role === UserRole::STAFF->value) {
                return true;
            }
        }

        if ($user->role === UserRole::STAFF->value) {
            // Staff can't delete themselves
            if ($model->role === UserRole::STAFF->value) {
                return false;
            }

            // Staff can't delete admins
            if ($model->role === UserRole::ADMIN->value) {
                return false;
            }
        }

        // Admins and staff can delete regular users
        return $model->role === UserRole::REGULAR->value && $this->isAdminOrStaff($user);
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        // Users can't restore itself
        if ($user->is($model)) {
            return false;
        }

        if ($user->role === UserRole::STAFF->value) {
            // Staffs can't restore regular users
            if ($model->role === UserRole::REGULAR->value) {
                return true;
            }

            // Staffs can't restore themselves
            if ($model->role === UserRole::STAFF->value) {
                return false;
            }

            // Staffs can't restore admins
            if ($model->role === UserRole::ADMIN->value) {
                return false;
            }
        }

        // Admins can restore any user
        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * @param  User  $user
     * @param  User  $model
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($user->is($model)) {
            return false;
        }

        if ($user->role === UserRole::ADMIN->value && $model->role === UserRole::ADMIN->value) {
            return false;
        }

        return $user->role === UserRole::ADMIN->value;
    }

    /**
     * @param  User  $user
     * @return bool
     */
    private function isAdminOrStaff(User $user): bool
    {
        return $user->role === UserRole::ADMIN->value
            || $user->role === UserRole::STAFF->value;
    }
}

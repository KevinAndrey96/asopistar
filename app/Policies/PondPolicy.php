<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Pond;
use App\Models\User;

/**
 * Farmers manage their own ponds; administrators may only read them.
 */
final class PondPolicy
{
    public function view(User $user, Pond $pond): bool
    {
        return $user->isAdmin() || $pond->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === Role::Farmer;
    }

    public function update(User $user, Pond $pond): bool
    {
        return $pond->user_id === $user->id;
    }

    public function stock(User $user, Pond $pond): bool
    {
        return $pond->user_id === $user->id && $pond->active;
    }
}

<?php

namespace App\Policies;

use App\Models\Cycle;
use App\Models\User;

/**
 * Only the pond owner writes the logbook, and only while the cycle is open.
 */
final class CyclePolicy
{
    public function view(User $user, Cycle $cycle): bool
    {
        return $user->isAdmin() || $cycle->pond->user_id === $user->id;
    }

    public function log(User $user, Cycle $cycle): bool
    {
        return $cycle->pond->user_id === $user->id && $cycle->isOpen();
    }
}

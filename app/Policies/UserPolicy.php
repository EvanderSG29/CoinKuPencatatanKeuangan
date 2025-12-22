<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view their own profile.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id_user === $user->id_user;
    }

    /**
     * Determine if the user can update their own profile.
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id_user === $user->id_user;
    }
}

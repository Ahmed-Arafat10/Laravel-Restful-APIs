<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use AdminActions;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id && $authUser->token()->client->personal_access_client;
    }

}

<?php

namespace App\Traits;

use App\Models\User;

trait AdminActions
{
    public function before(User $user, $ability)
    {
        if ($user->isAdmin())
            return true;
    }
}

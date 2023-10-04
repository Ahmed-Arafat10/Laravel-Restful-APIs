<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
    use AdminActions;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function sale(User $user, User $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function editProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }
}

<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    use AdminActions;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer->id || $user->id === $transaction->buyer->product->id;
    }
}

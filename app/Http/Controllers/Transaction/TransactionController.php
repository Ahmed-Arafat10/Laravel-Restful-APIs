<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;

class TransactionController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api','scope:read-general'])->only(['show']);

        $this->middleware(['can:view,transaction'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->allowedAdminAction();

        $product = Transaction::paginate(10);
        return $this->showAllPaginate($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }
}

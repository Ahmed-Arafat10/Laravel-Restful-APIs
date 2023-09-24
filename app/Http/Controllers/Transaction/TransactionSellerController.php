<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->seller;
        return $this->showOne($categories);
    }
}
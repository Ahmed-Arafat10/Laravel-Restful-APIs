<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;

class BuyerProductController extends ApiController
{
    // danger: implement it in postman
    public function index(Buyer $buyer)
    {
        //$products = $buyer->transactions->product; // Error as $buyer->transactions returns a collection
        $products = $buyer->transactions()
            ->with('product')// eager loading
            ->get()
            ->pluck('product');
        return $this->showAll($products);
    }
}

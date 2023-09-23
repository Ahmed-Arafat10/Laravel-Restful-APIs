<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerSellerController extends ApiController
{
    // danger: implement it in postman
    public function index(Buyer $buyer)
    {
        //$products = $buyer->transactions->product; // Error as $buyer->transactions returns a collection
        $sellers = $buyer->transactions()
            ->with('product.seller')// eager loading
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();
        return $this->showAll($sellers);
    }
}

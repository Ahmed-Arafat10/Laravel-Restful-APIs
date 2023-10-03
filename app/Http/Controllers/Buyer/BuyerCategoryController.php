<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api','scope:read-general'])->only(['index']);
    }
    // danger: implement it in postman
    public function index(Buyer $buyer)
    {
        //$products = $buyer->transactions->product; // Error as $buyer->transactions returns a collection
        $sellers = $buyer->transactions()
            ->with('product.categories')// eager loading
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();
        return $this->showAll($sellers);
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api','scope:read-general'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seller = Seller::has('products')->get(); // transactions() is a method in Buyer model
        return $this->showAll($seller);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        $seller = Seller::has('products')->findOrFail($seller->id); // transactions() is a method in Buyer model
        return $this->showOne($seller);
    }
}

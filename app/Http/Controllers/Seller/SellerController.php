<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerController extends ApiController
{
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
        //$seller = Seller::has('products')->findOrFail($id); // transactions() is a method in Buyer model
        return $this->showOne($seller);
    }
}

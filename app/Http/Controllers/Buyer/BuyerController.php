<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buyer = Buyer::has('transactions')->get(); // transactions() is a method in Buyer model
        return $this->showAll($buyer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer)
    {
        //$buyer = Buyer::has('transactions')->findOrFail($id); // transactions() is a method in Buyer model
        return $this->showOne($buyer);
    }

}

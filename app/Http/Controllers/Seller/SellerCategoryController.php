<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api','scope:read-general'])->only(['index']);
        $this->middleware(['can:view,seller'])->only(['index']);
    }   public function index(Seller $seller)
    {
        $categories = $seller->products()
            ->whereHas('categories')
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            ->unique('id')
            ->values();
        return $this->showAll($categories);
    }
}

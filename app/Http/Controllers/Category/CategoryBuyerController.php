<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryBuyerController extends Controller
{
    public function index(Category $category)
    {
        $sellers = $category->products()
            ->whereHas('transactions') // danger: search for it
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();
        return $this->showAll($sellers);
    }
}

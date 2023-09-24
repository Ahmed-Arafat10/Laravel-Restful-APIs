<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;

class CategoryTransactionController extends ApiController
{
    public function index(Category $category)
    {
        $sellers = $category->products()
            ->whereHas('transactions') // danger: search for it
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();
        return $this->showAll($sellers);
    }
}

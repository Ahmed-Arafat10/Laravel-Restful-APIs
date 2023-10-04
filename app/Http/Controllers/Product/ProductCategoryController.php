<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['index']);
        $this->middleware(['auth:api', 'scope:manage-products'])->except(['index']);

        $this->middleware(['can:add-category,product'])->only(['update']);
        $this->middleware(['can:delete-category,product'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Category $category)
    {
        # will add the relationship each time (even if the relationship already exists between both product & category)
        //$product->categories()->attach([$category->id]);
        # will remove all relationship between that product & other categories then add the new one
        //$product->categories()->sync([$category->id]);
        # will not remove all relationship between that product & other categories then add the new one
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id))
            return $this->errorResponse('The specified category is not a category of this product', 404);

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}

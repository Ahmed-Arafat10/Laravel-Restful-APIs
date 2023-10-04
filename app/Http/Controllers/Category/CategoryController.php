<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['index', 'show']);
    }

    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->allowedAdminAction();
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->errorResponse($validator->getMessageBag(), 409);

        $newCategory = Category::create($request->only(['name', 'description']));

        return $this->showOne($newCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->allowedAdminAction();

        $category->fill($request->only(['name', 'description']));

        if ($category->isClean())
            return $this->errorResponse('you need to specify any different value to update', 422);

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->allowedAdminAction();

        $category->delete();
        return $this->showOne($category);
    }
}

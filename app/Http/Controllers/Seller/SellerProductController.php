<?php

namespace App\Http\Controllers\Seller;

use App\Exceptions\customFormValidationException;
use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:manage-products'])->except(['index']);

        $this->middleware(['can:view,seller'])->only(['index']);
        $this->middleware(['can:sale,seller'])->only(['store']);
        $this->middleware(['can:edit-project,seller'])->only(['update']);
        $this->middleware(['can:delete-project,seller'])->only(['destroy']);
    }

    /**
     * @throws AuthorizationException
     * @throws customFormValidationException
     */
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read-general') ||
            request()->user()->tokenCan('manage-products]')) {
            $products = $seller->products;

            return $this->showAll($products);
        }
        throw new AuthorizationException('Invalid scope(s)');
    }

    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2000'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return $this->errorResponse($validator->getMessageBag(), 409);

        $img = $request->file('image');
        $img->store('img', 'myImg');
        //$img->store('img', 'public');
        $data = $request->only(['name', 'description', 'quantity', 'image']);
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $img->hashName();
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, 201);
    }

    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => [Rule::in([Product::UNAVAILABLE_PRODUCT, Product::AVAILABLE_PRODUCT])],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return $this->errorResponse($validator->getMessageBag(), 409);

        $this->checkSeller($seller, $product);

        $product->fill($request->only(['name', 'description', 'quantity']));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && !$product->categories()->count())
                return $this->errorResponse('An active product must have at least one category', 409);
        }

        if ($request->hasFile('image')) {
            if (Storage::disk('myImg')->exists('img/' . $product->image))
                Storage::disk('myImg')->delete('img/' . $product->image);
            $img = $request->file('image');
            $img->store('img', 'myImg');
            $product->image = $img->hashName();
        }

        if ($product->isClean())
            return $this->errorResponse('You need to specify a different values to update', 422);

        $product->save();

        return $this->showOne($product, 201);

    }

    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        Storage::disk('myImg')->delete('img/' . $product->image);
        $product->delete();
        return $this->showOne($seller);
    }

    protected function checkSeller(Seller &$seller, Product &$product)
    {
        if ($product->seller_id != $seller->id)
            throw new AuthorizationException('the specified seller is not the actual seller of the product');
    }
}

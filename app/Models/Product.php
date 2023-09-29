<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const AVAILABLE_PRODUCT = 1;
    const UNAVAILABLE_PRODUCT = 0;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];
    protected $hidden = ['pivot'];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function updateEvent()
    {
        Product::updated(function ($product) {
            if (!$product->quantity && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;
                $product->save();
            }
        });
    }
}

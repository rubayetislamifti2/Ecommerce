<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'price',
        'stock',
        'status',
        'stripe_product_id',
        'stripe_price_id',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
}

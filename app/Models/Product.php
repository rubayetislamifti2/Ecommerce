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
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
}

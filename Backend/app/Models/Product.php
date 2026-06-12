<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductTier;
use App\Models\ProductVariant;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'category',
        'base_price',
        'description',
        'image_path',
        'status',
    ];

    public function tiers()
    {
        return $this->hasMany(ProductTier::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

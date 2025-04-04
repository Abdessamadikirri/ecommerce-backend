<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category',
        'image'
    ];

    public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems():HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}

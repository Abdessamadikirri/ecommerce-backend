<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(user::class);
    }
    public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payement():HasOne
    {
        return $this->hasOne(Payment::class);
    }
}

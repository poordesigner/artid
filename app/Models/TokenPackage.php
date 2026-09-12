<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'description',
    'tokens',
    'price_usd',
    'paddle_product_id',
    'paddle_price_id',
    'is_active',
    'sort_order',
])]
class TokenPackage extends Model
{
    public function priceInCents(): int
    {
        return (int) round($this->price_usd * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
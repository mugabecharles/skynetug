<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TldPricing extends Model
{
    protected $table = 'tld_pricing';

    protected $fillable = [
        'tld', 'register_price', 'renew_price', 'transfer_price',
        'currency', 'is_active', 'is_popular', 'sort_order',
    ];

    protected $casts = [
        'register_price'  => 'decimal:2',
        'renew_price'     => 'decimal:2',
        'transfer_price'  => 'decimal:2',
        'is_active'       => 'boolean',
        'is_popular'      => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('tld');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $order_number
 * @property int $user_id
 * @property string $status
 * @property float $subtotal
 * @property float $tax
 * @property float $discount
 * @property float $total
 * @property string $currency
 * @property int|null $coupon_id
 * @property string|null $notes
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'coupon_id',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax'      => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Order> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Coupon, Order> */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /** @return HasMany<OrderItem> */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasMany<Invoice> */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

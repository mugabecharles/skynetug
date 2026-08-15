<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $hosting_package_id
 * @property string $item_type
 * @property string $item_name
 * @property string $billing_cycle
 * @property float $unit_price
 * @property int $quantity
 * @property float $total
 * @property string|null $service_start
 * @property string|null $service_end
 * @property array|null $meta
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'hosting_package_id',
        'item_type',
        'item_name',
        'billing_cycle',
        'unit_price',
        'quantity',
        'total',
        'service_start',
        'service_end',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_price'    => 'decimal:2',
        'total'         => 'decimal:2',
        'quantity'      => 'integer',
        'meta'          => 'array',
        'service_start' => 'date',
        'service_end'   => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<Order, OrderItem> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<HostingPackage, OrderItem> */
    public function hostingPackage(): BelongsTo
    {
        return $this->belongsTo(HostingPackage::class);
    }
}

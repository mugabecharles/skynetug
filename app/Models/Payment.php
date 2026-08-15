<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string $transaction_id
 * @property int $user_id
 * @property int|null $invoice_id
 * @property string $gateway
 * @property float $amount
 * @property string $currency
 * @property string $status
 * @property string|null $gateway_transaction_ref
 * @property string|null $phone_number
 * @property string|null $gateway_response
 * @property string|null $paid_at
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_id',
        'user_id',
        'invoice_id',
        'gateway',
        'amount',
        'currency',
        'status',
        'gateway_transaction_ref',
        'phone_number',
        'gateway_response',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Payment> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Invoice, Payment> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /** @return HasMany<Refund> */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}

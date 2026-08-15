<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Refund
 *
 * @property int $id
 * @property int $payment_id
 * @property int|null $invoice_id
 * @property int $processed_by
 * @property float $amount
 * @property string $reason
 * @property string $status
 * @property string|null $processed_at
 */
class Refund extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'payment_id',
        'invoice_id',
        'processed_by',
        'amount',
        'reason',
        'status',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount'       => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<Payment, Refund> */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /** @return BelongsTo<Invoice, Refund> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * The admin/staff member who processed this refund.
     *
     * @return BelongsTo<User, Refund>
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

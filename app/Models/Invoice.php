<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $user_id
 * @property int|null $order_id
 * @property string $status
 * @property string $type
 * @property float $subtotal
 * @property float $tax
 * @property float $credit
 * @property float $total
 * @property string $currency
 * @property string $date_created
 * @property string $date_due
 * @property string|null $date_paid
 * @property float $late_fee
 * @property string|null $late_fee_applied_at
 * @property string|null $notes
 */
class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_number',
        'user_id',
        'order_id',
        'status',
        'type',
        'subtotal',
        'tax',
        'credit',
        'total',
        'currency',
        'date_created',
        'date_due',
        'date_paid',
        'late_fee',
        'late_fee_applied_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal'             => 'decimal:2',
        'tax'                  => 'decimal:2',
        'credit'               => 'decimal:2',
        'total'                => 'decimal:2',
        'late_fee'             => 'decimal:2',
        'date_created'         => 'date',
        'date_due'             => 'date',
        'date_paid'            => 'date',
        'late_fee_applied_at'  => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Static helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a unique invoice number in the format INV-YYYYMMDD-XXXXX.
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $last   = static::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('invoice_number');

        $sequence = $last ? ((int) substr($last, -5)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Invoice> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Order, Invoice> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return HasMany<InvoiceItem> */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

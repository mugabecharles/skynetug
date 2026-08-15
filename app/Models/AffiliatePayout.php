<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AffiliatePayout
 *
 * @property int $id
 * @property int $affiliate_id
 * @property int|null $processed_by
 * @property float $amount
 * @property string $status
 * @property string|null $payment_method
 * @property string|null $notes
 * @property string|null $paid_at
 */
class AffiliatePayout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'affiliate_id',
        'processed_by',
        'amount',
        'status',
        'payment_method',
        'notes',
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

    /** @return BelongsTo<Affiliate, AffiliatePayout> */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * The admin/staff member who processed this payout.
     *
     * @return BelongsTo<User, AffiliatePayout>
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

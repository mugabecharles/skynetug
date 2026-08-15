<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AffiliateReferral
 *
 * @property int $id
 * @property int $affiliate_id
 * @property int $referred_user_id
 * @property int|null $order_id
 * @property float $commission
 * @property string $status
 */
class AffiliateReferral extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'affiliate_id',
        'referred_user_id',
        'order_id',
        'commission',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<Affiliate, AffiliateReferral> */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /** @return BelongsTo<User, AffiliateReferral> */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /** @return BelongsTo<Order, AffiliateReferral> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

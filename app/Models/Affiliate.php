<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Affiliate
 *
 * @property int $id
 * @property int $user_id
 * @property string $referral_code
 * @property float $commission_rate
 * @property float $balance
 * @property float $total_earned
 * @property float $total_paid
 * @property int $total_referrals
 * @property string $status
 */
class Affiliate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'referral_code',
        'commission_rate',
        'balance',
        'total_earned',
        'total_paid',
        'total_referrals',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'commission_rate' => 'decimal:2',
        'balance'         => 'decimal:2',
        'total_earned'    => 'decimal:2',
        'total_paid'      => 'decimal:2',
        'total_referrals' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Affiliate> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<AffiliateReferral> */
    public function referrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class);
    }

    /** @return HasMany<AffiliatePayout> */
    public function payouts(): HasMany
    {
        return $this->hasMany(AffiliatePayout::class);
    }
}

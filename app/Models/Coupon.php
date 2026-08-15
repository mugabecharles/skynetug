<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property float $value
 * @property float $minimum_order
 * @property int $usage_limit
 * @property int $usage_count
 * @property string|null $starts_at
 * @property string|null $expires_at
 * @property array|null $applicable_packages
 * @property string $applicable_billing
 * @property bool $is_active
 */
class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'minimum_order',
        'usage_limit',
        'usage_count',
        'starts_at',
        'expires_at',
        'applicable_packages',
        'applicable_billing',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value'                => 'decimal:2',
        'minimum_order'        => 'decimal:2',
        'usage_limit'          => 'integer',
        'usage_count'          => 'integer',
        'applicable_packages'  => 'array',
        'is_active'            => 'boolean',
        'starts_at'            => 'date',
        'expires_at'           => 'date',
    ];

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Determine whether this coupon is currently valid:
     *   - must be active
     *   - must be within its active date range (if set)
     *   - must not have exceeded its usage limit (0 = unlimited)
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = Carbon::today();

        if ($this->starts_at && $today->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $today->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit > 0 && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return HasMany<Order> */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

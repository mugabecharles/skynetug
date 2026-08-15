<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\HostingPackage
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string|null $description
 * @property float $price_monthly
 * @property float $price_yearly
 * @property float $price_biennially
 * @property int $disk_space_mb
 * @property int $bandwidth_mb
 * @property int $email_accounts
 * @property int $databases
 * @property int $subdomains
 * @property int $addon_domains
 * @property int $parked_domains
 * @property bool $ssl_included
 * @property bool $softaculous_included
 * @property bool $backup_included
 * @property array|null $features
 * @property bool $is_featured
 * @property bool $is_active
 * @property int $sort_order
 */
class HostingPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'price_monthly',
        'price_yearly',
        'price_biennially',
        'disk_space_mb',
        'bandwidth_mb',
        'email_accounts',
        'databases',
        'subdomains',
        'addon_domains',
        'parked_domains',
        'ssl_included',
        'softaculous_included',
        'backup_included',
        'features',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features'              => 'array',
        'ssl_included'          => 'boolean',
        'softaculous_included'  => 'boolean',
        'backup_included'       => 'boolean',
        'is_featured'           => 'boolean',
        'is_active'             => 'boolean',
        'price_monthly'         => 'decimal:2',
        'price_yearly'          => 'decimal:2',
        'price_biennially'      => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return HasMany<HostingAccount> */
    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    /** @return HasMany<OrderItem> */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

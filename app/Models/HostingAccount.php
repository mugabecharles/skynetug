<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\HostingAccount
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $order_id
 * @property int|null $hosting_package_id
 * @property int|null $server_id
 * @property string $domain
 * @property string $username
 * @property string|null $cpanel_password
 * @property string $status
 * @property string $billing_cycle
 * @property float $price
 * @property string|null $registration_date
 * @property string|null $next_due_date
 * @property string|null $termination_date
 * @property string|null $cpanel_url
 * @property string|null $suspension_reason
 * @property int $disk_used_mb
 * @property int $bandwidth_used_mb
 * @property string|null $cpanel_created_at
 * @property string|null $suspended_at
 */
class HostingAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'hosting_package_id',
        'server_id',
        'domain',
        'username',
        'cpanel_password',
        'status',
        'billing_cycle',
        'price',
        'registration_date',
        'next_due_date',
        'termination_date',
        'cpanel_url',
        'suspension_reason',
        'disk_used_mb',
        'bandwidth_used_mb',
        'cpanel_created_at',
        'suspended_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'              => 'decimal:2',
        'registration_date'  => 'date',
        'next_due_date'      => 'date',
        'termination_date'   => 'date',
        'cpanel_created_at'  => 'datetime',
        'suspended_at'       => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'cpanel_password',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, HostingAccount> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Order, HostingAccount> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<HostingPackage, HostingAccount> */
    public function hostingPackage(): BelongsTo
    {
        return $this->belongsTo(HostingPackage::class);
    }

    /** @return BelongsTo<Server, HostingAccount> */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /** @return HasMany<SslCertificate> */
    public function sslCertificates(): HasMany
    {
        return $this->hasMany(SslCertificate::class);
    }

    /** @return HasMany<EmailAccount> */
    public function emailAccounts(): HasMany
    {
        return $this->hasMany(EmailAccount::class);
    }

    /** @return HasMany<Backup> */
    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }
}

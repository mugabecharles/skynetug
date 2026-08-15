<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Domain
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $order_id
 * @property string $domain_name
 * @property string $tld
 * @property string $status
 * @property string $registration_type
 * @property float $registration_price
 * @property float $renewal_price
 * @property string|null $registration_date
 * @property string|null $expiry_date
 * @property string|null $registrar
 * @property string|null $registrar_id
 * @property string|null $epp_code
 * @property bool $is_locked
 * @property bool $whois_privacy
 * @property bool $auto_renew
 * @property string|null $nameserver_1
 * @property string|null $nameserver_2
 * @property string|null $nameserver_3
 * @property string|null $nameserver_4
 */
class Domain extends Model
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
        'domain_name',
        'tld',
        'status',
        'registration_type',
        'registration_price',
        'renewal_price',
        'registration_date',
        'expiry_date',
        'registrar',
        'registrar_id',
        'epp_code',
        'is_locked',
        'whois_privacy',
        'auto_renew',
        'nameserver_1',
        'nameserver_2',
        'nameserver_3',
        'nameserver_4',
        'expiry_reminder_30_sent',
        'expiry_reminder_7_sent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registration_price'       => 'decimal:2',
        'renewal_price'            => 'decimal:2',
        'registration_date'        => 'date',
        'expiry_date'              => 'date',
        'is_locked'                => 'boolean',
        'whois_privacy'            => 'boolean',
        'auto_renew'               => 'boolean',
        'expiry_reminder_30_sent'  => 'datetime',
        'expiry_reminder_7_sent'   => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'epp_code',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Domain> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Order, Domain> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return HasMany<DnsRecord> */
    public function dnsRecords(): HasMany
    {
        return $this->hasMany(DnsRecord::class);
    }
}

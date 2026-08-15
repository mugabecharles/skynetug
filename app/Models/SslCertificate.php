<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SslCertificate
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $hosting_account_id
 * @property string $domain
 * @property string $type
 * @property string $status
 * @property string|null $provider
 * @property string|null $certificate
 * @property string|null $private_key
 * @property string|null $ca_bundle
 * @property string|null $issued_at
 * @property string|null $expires_at
 * @property string|null $renewal_started_at
 * @property string|null $expiry_reminder_sent_at
 */
class SslCertificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'hosting_account_id',
        'domain',
        'type',
        'status',
        'provider',
        'certificate',
        'private_key',
        'ca_bundle',
        'issued_at',
        'expires_at',
        'renewal_started_at',
        'expiry_reminder_sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at'               => 'datetime',
        'expires_at'              => 'datetime',
        'renewal_started_at'      => 'datetime',
        'expiry_reminder_sent_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'private_key',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, SslCertificate> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<HostingAccount, SslCertificate> */
    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }
}

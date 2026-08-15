<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\EmailAccount
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $hosting_account_id
 * @property string $email
 * @property string $domain
 * @property string $username
 * @property int $quota_mb
 * @property int $used_mb
 * @property string $status
 * @property array|null $forwarding_rules
 * @property string|null $quota_warning_sent_at
 */
class EmailAccount extends Model
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
        'email',
        'domain',
        'username',
        'quota_mb',
        'used_mb',
        'status',
        'forwarding_rules',
        'quota_warning_sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quota_mb'              => 'integer',
        'used_mb'               => 'integer',
        'forwarding_rules'      => 'array',
        'quota_warning_sent_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, EmailAccount> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<HostingAccount, EmailAccount> */
    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }
}

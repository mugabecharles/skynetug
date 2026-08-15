<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Server
 *
 * @property int $id
 * @property string $name
 * @property string $hostname
 * @property string $ip_address
 * @property string $type
 * @property string|null $username
 * @property string|null $api_hash
 * @property int $max_accounts
 * @property bool $is_active
 * @property bool $nameserver_1
 * @property string|null $ns1
 * @property string|null $ns2
 */
class Server extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'hostname',
        'ip_address',
        'type',
        'username',
        'api_hash',
        'max_accounts',
        'is_active',
        'nameserver_1',
        'ns1',
        'ns2',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'    => 'boolean',
        'nameserver_1' => 'boolean',
        'max_accounts' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return HasMany<HostingAccount> */
    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }
}

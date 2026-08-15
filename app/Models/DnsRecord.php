<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\DnsRecord
 *
 * @property int $id
 * @property int $domain_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property int $ttl
 * @property int $priority
 */
class DnsRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'domain_id',
        'type',
        'name',
        'value',
        'ttl',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ttl'      => 'integer',
        'priority' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<Domain, DnsRecord> */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}

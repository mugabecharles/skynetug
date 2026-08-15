<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AuditLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action_type
 * @property string $resource_type
 * @property string|null $resource_id
 * @property string $description
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 */
class AuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'resource_type',
        'resource_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, AuditLog> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

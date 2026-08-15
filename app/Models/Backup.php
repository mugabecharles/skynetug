<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Backup
 *
 * @property int $id
 * @property int $user_id
 * @property int $hosting_account_id
 * @property string $type
 * @property string|null $schedule
 * @property string $status
 * @property string|null $filename
 * @property string|null $storage_path
 * @property int $size_bytes
 * @property string|null $started_at
 * @property string|null $completed_at
 * @property string|null $error_message
 */
class Backup extends Model
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
        'type',
        'schedule',
        'status',
        'filename',
        'storage_path',
        'size_bytes',
        'started_at',
        'completed_at',
        'error_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size_bytes'   => 'integer',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, Backup> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<HostingAccount, Backup> */
    public function hostingAccount(): BelongsTo
    {
        return $this->belongsTo(HostingAccount::class);
    }
}

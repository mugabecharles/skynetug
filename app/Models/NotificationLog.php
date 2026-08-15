<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\NotificationLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $type
 * @property string $event
 * @property string $recipient
 * @property string|null $subject
 * @property string $status
 * @property string|null $error
 * @property string|null $sent_at
 */
class NotificationLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'event',
        'recipient',
        'subject',
        'status',
        'error',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<User, NotificationLog> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

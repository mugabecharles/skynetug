<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\TicketReply
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $message
 * @property bool $is_staff_reply
 */
class TicketReply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_staff_reply',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_staff_reply' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<SupportTicket, TicketReply> */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /** @return BelongsTo<User, TicketReply> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<TicketAttachment> */
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'reply_id');
    }
}

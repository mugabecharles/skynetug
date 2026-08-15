<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\SupportTicket
 *
 * @property int $id
 * @property string $ticket_number
 * @property int $user_id
 * @property int|null $assigned_to
 * @property string $subject
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property string $description
 * @property string|null $last_reply_at
 */
class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'subject',
        'category',
        'priority',
        'status',
        'description',
        'last_reply_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * The customer who opened the ticket.
     *
     * @return BelongsTo<User, SupportTicket>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The staff member assigned to the ticket.
     *
     * @return BelongsTo<User, SupportTicket>
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** @return HasMany<TicketReply> */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
}

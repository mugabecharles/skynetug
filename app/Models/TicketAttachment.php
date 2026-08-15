<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TicketAttachment
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property int|null $reply_id
 * @property string $filename
 * @property string $original_name
 * @property string $mime_type
 * @property int $file_size
 */
class TicketAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'reply_id',
        'filename',
        'original_name',
        'mime_type',
        'file_size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return BelongsTo<SupportTicket, TicketAttachment> */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /** @return BelongsTo<TicketReply, TicketAttachment> */
    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class, 'reply_id');
    }
}

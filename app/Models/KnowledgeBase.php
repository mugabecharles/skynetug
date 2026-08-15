<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\KnowledgeBase
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $category
 * @property string $status
 * @property int $views
 * @property int $created_by
 */
class KnowledgeBase extends Model
{
    use HasFactory;

    /**
     * The database table used by this model.
     *
     * @var string
     */
    protected $table = 'knowledge_base';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'status',
        'views',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'views' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * The staff member who created this article.
     *
     * @return BelongsTo<User, KnowledgeBase>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

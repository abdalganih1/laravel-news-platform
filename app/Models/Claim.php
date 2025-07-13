<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Claim extends Model
{
    use HasFactory;

    protected $primaryKey = 'claim_id';

    protected $fillable = [
        'user_id',
        'title',
        'external_url',
        'reported_text',
        'user_notes',
        'claim_status',
        'admin_notes',
        'resolution_post_id',
        'reviewed_by_user_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the claim.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the user (admin/editor) who reviewed the claim.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id', 'user_id');
    }

    /**
     * Get the post that was created as a resolution for this claim.
     */
    public function resolutionPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'resolution_post_id', 'post_id');
    }

    /**
     * Get the images attached to the claim.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ClaimImage::class, 'claim_id', 'claim_id');
    }
}
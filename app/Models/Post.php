<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'region_id',
        'title',
        'text_content',
        'post_status',
        'corrected_post_id',
    ];

    /**
     * Get the user that owns the post (author).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Adjust 'user_id' if user PK is 'id'
    }

    /**
     * Get the region associated with the post.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * Get the images for the post.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class, 'post_id', 'post_id');
    }

    /**
     * Get the videos for the post.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(PostVideo::class, 'post_id', 'post_id');
    }

    /**
     * Get the claim associated with the post.
     */
    public function claim(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Claim::class, 'resolution_post_id', 'post_id');
    }

    /**
     * Get the favorites for the post.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'post_id', 'post_id');
    }

    /**
     * Get the post that corrects this post (if this post is fake).
     */
    public function correction(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'corrected_post_id', 'post_id');
    }

    /**
     * Get the fake posts that this post corrects (if this post is real).
     */
    public function correctedPosts(): HasMany // Renamed from correctedBy for clarity
    {
        return $this->hasMany(Post::class, 'corrected_post_id', 'post_id');
    }
}
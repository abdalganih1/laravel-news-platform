<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImage extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'image_id';

    /**
     * Indicates if the model should be timestamped.
     * Set to false if only using 'created_at' from the migration.
     *
     * @var bool
     */
    public $timestamps = false; // Only created_at is managed by DB default

     /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     * Set to null because we don't have it in the table.
     *
     * @var string|null
     */
    const UPDATED_AT = null;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['claim_id', 'image_url', 'sizes', 'caption'];
    protected $casts = [
        'sizes' => 'array',
    ];

    /**
     * Get the post that owns the image.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
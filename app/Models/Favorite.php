<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $primaryKey = 'favorite_id';

    // Keep $timestamps = false; if you only have created_at and it's managed by DB default
    // public $timestamps = false;

    // However, explicitly define CREATED_AT to tell Laravel the column name
    const CREATED_AT = 'created_at'; // <--- Keep this


    // Ensure UPDATED_AT is null if you don't have that column
    const UPDATED_AT = null; // <--- Keep this

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // --- Add this line to cast created_at to Carbon ---
        'created_at' => 'datetime',
        // -------------------------------------------------
    ];


    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
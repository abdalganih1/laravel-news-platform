<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'region_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'governorate_id',
        'name',
        'gps_coordinates',
    ];

    /**
     * Get the governorate that owns the region.
     */
    public function governorate(): BelongsTo
    {
        // Foreign key is 'governorate_id', owner key is 'governorate_id' on the governorates table
        return $this->belongsTo(Governorate::class, 'governorate_id', 'governorate_id');
    }

    /**
     * Get the posts for the region.
     */
    public function posts(): HasMany
    {
        // Foreign key in posts table is 'region_id'
        return $this->hasMany(Post::class, 'region_id', 'region_id');
    }
}
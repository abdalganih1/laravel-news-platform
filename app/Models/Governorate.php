<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Governorate extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'governorate_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the regions for the governorate.
     */
    public function regions(): HasMany
    {
        // Assuming 'governorate_id' is the foreign key in the 'regions' table
        // and 'governorate_id' is the primary key in the 'governorates' table.
        return $this->hasMany(Region::class, 'governorate_id', 'governorate_id');
    }

    /**
     * Get the users for the governorate.
     */
    public function users(): HasMany
    {
        // Assuming 'governorate_id' is the foreign key in the 'users' table
        return $this->hasMany(User::class, 'governorate_id', 'governorate_id');
    }
}
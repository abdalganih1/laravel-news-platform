<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicitly defining is good practice.
     *
     * @var string
     */
    protected $table = 'site_info';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'info_id';

    /**
     * Indicates if the model should be timestamped.
     * Set to true because we have 'updated_at'.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The name of the "created at" column.
     * Set to null because we don't have it.
     *
     * @var string|null
     */
    const CREATED_AT = null;

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'contact_phone',
        'contact_email',
        'website_url',
    ];
}
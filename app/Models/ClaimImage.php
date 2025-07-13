<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimImage extends Model
{
    use HasFactory;
    protected $primaryKey = 'image_id';
    protected $fillable = ['claim_id', 'image_url', 'caption'];
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Uncomment if email verification is used
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Keep if using API authentication (Sanctum)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage; // استيراد Storage

// Add 'implements MustVerifyEmail' if using email verification features from Breeze
class User extends Authenticatable /* implements MustVerifyEmail */
{
    use HasApiTokens, HasFactory, Notifiable; // Keep HasApiTokens if using API

    /**
     * The primary key associated with the table.
     * Laravel default is 'id', change only if your migration changed it to 'user_id'.
     *
     * @var string
     */
    // protected $primaryKey = 'user_id'; // Uncomment ONLY if you renamed the PK in migration
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     * Add your custom fields here.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', // Added
        'last_name', // Added
        'phone_number', // Added
        'email', // Standard
        'password', // Standard (Hashing is handled automatically)
        'user_role', // Added
        'governorate_id', // Added
        'date_of_birth', // Added
        'notes', // Added
        'profile_image_path', // <-- أضف هذا
        // Keep 'name' here if you didn't replace it with first_name/last_name
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date', // Cast date_of_birth to Date object
        'password' => 'hashed', // Standard Laravel 10+ password casting
    ];

    // --- دالة مساعدة للحصول على رابط الصورة الشخصية ---
    // تستخدم Accesssor: يتم استدعاؤها كـ $user->profile_image_url
public function getProfileImageUrlAttribute(): string
{
    // 1. Check if profile_image_path column has a value
    // Use $this->attributes['profile_image_path'] to access the raw column value directly
    // This avoids potential recursion if the accessor is called internally
    $profileImagePath = $this->attributes['profile_image_path'] ?? null;

    if ($profileImagePath) {
        // 2. Check if the file actually exists in the 'public' disk
        // Use Storage::disk('public') explicitly
        if (Storage::disk('public')->exists($profileImagePath)) {
             // 3. If it exists, generate the public URL using asset()
             // asset() requires the path relative to the public directory
             // The path returned by ->store('profile_images', 'public') is already relative to storage/app/public
             return asset('storage/' . $profileImagePath); // <--- يجب أن يكون الرابط بهذا الشكل
        }
    }

    // 4. If no path is stored, or file doesn't exist, return the UI-Avatars link
    $name = trim(($this->attributes['first_name'] ?? '') . ' ' . ($this->attributes['last_name'] ?? '')); // Use attributes for safety
    if (empty($name)) {
        $name = 'U'; // Default name if first/last are empty
    }
    return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=4F46E5&background=EEF2FF';
}

    /**
     * Get the governorate associated with the user.
     */
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'governorate_id');
    }

    /**
     * Get the posts for the user.
     */
    public function posts(): HasMany
    {
        // Foreign key in posts table is 'user_id'
        return $this->hasMany(Post::class, 'user_id', $this->getKeyName()); // Use getKeyName() for PK
    }

    /**
     * Get the claims submitted by the user.
     */
    public function claims(): HasMany
    {
        // Foreign key in claims table is 'user_id'
        return $this->hasMany(Claim::class, 'user_id', $this->getKeyName());
    }

    /**
     * Get the claims reviewed by the user (if they are an editor/admin).
     */
    public function reviewedClaims(): HasMany
    {
        // Foreign key in claims table is 'reviewed_by_user_id'
        return $this->hasMany(Claim::class, 'reviewed_by_user_id', $this->getKeyName());
    }

    /**
     * Get the favorites for the user.
     */
    public function favorites(): HasMany
    {
        // Foreign key in favorites table is 'user_id'
        return $this->hasMany(Favorite::class, 'user_id', $this->getKeyName());
    }
     public function isRequestingEditor(): bool
    {
        // يمكنك تعريف حالة جديدة مثل 'pending_editor' أو 'requesting_editor'
        // أو استخدام حقل منفصل مثل 'requested_editor_role_at' timestamp
        // للتبسيط، سنستخدم حالة جديدة في user_role
        return $this->user_role === 'pending_editor';
    }
    
}
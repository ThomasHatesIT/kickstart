<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Review; // Add this at the top
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CartItem; // Add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'profile_photo_path', // Add this
    'face_photo_path',    // Add this
    'status',
    'phone',
    'address',
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
    ];

      public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function needsApproval()
    {
        return $this->status === 'pending' && $this->hasRole('seller');
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
     public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orders(): HasMany
{
    return $this->hasMany(Order::class);
}
   public function isBanned(): bool
    {
        return $this->status === 'banned';
    }
    public function verificationDocuments(): HasMany
    {
        // This tells Laravel that a User can have many UserVerificationDocument records,
        // linked by the 'user_id' foreign key.
        return $this->hasMany(UserVerificationDocument::class);
    }
}

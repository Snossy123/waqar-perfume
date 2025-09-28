<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'otp_hash',
        'otp_expires_at',
        'is_active',
        'phone',
        'gender',
        'date_of_birth', 
        'password',
        'updated_profile',
        'is_verified',
        'is_active',
        'completed_registration',
        'fcm_token',
        'wallet'
    ];
    public $translatable = ['name', 'gender'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function favouriteCars()
    {
        return $this->belongsToMany(Car::class, 'car_user_favourites')->withTimestamps();
    }
}

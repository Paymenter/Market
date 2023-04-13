<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'admin',
        'role',
        'avatar',
        'bio',	
        'sbio',
        'currency',
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

    /**
     * All the resources that belong to the user.
     */
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * All the orders that belong to the user.
     */
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    /**
     * All the reviews that belong to the user.
     */
    public function reviews()
    {
        return $this->hasMany(ResourceReview::class);
    }
}

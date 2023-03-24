<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slogan',
        'views',
        'downloads',
        'price',
        'status',
        'image',
        'type',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'status',
        'views',
        'downloads',
    ];

    
    /**
     * The user that owns the resource.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
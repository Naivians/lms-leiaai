<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Classes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_number',
        'name',
        'email',
        'password',
        'contact',
        'img',
        'role',
        'gender',
        'isVerified',
        'verification_token',
    ];



    /**
     *
     *
     *
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
        'password' => 'hashed',
    ];


    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_users', 'user_id', 'class_id')
            ->withTimestamps();
    }

    public function activeClasses()
    {
        return $this->belongsToMany(Classes::class, 'class_users', 'user_id', 'class_id')
            ->where('classes.active', 1)
            ->withTimestamps();
    }

    public function inactiveClasses()
    {
        return $this->belongsToMany(Classes::class, 'class_users', 'user_id', 'class_id')
            ->where('classes.active', 0)
            ->withTimestamps();
    }


}

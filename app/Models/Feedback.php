<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Feedback extends Model
{

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feedback) {
            if (empty($feedback->anonymous_name)) {
                $feedback->anonymous_name = 'User_' . Str::random(6);
            }
        });
    }

    protected $fillable = ['user_id', 'feedback', 'anonymous_name'];
}

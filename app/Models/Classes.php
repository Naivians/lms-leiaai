<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'course_name',
        'class_description',
        'user_id',
        'active',
        'class_code',
        'file_path',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_users', 'class_id', 'user_id')
            ->withTimestamps();
    }


    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'class_id');
    }

}

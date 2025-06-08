<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'class_description',
        'cgi_id',
        'course_id',
        'active',
        'class_code',
        'file_path',
    ];
}

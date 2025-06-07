<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'course_name',
        'course_description',
        'course_code',
    ];
}

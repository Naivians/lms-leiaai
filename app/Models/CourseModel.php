<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\lessons;

class CourseModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'course_name',
        'course_description',
        'course_code',
    ];

    public function lessons()
    {
        return $this->hasMany(lessons::class, 'course_id');
    }

    public static function get_course_id($course_name)
    {
        $course = self::select('id', 'course_name')->where('course_name', $course_name)->first();
        return $course ? $course->id : null;
    }
}

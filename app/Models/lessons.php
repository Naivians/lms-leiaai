<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseModel;
use App\Models\Material;
class lessons extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
    ];

    public function course(){
        return $this->belongsTo(CourseModel::class, 'course_id');
    }

    public function materials(){
        return $this->hasMany(Material::class, 'lessons_id', 'id');
    }
}

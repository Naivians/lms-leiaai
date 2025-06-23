<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'total',
        'class_id',
        'assessment_time',
        'assessment_date',
    ];


    public function question(){
        return $this->hasMany(Question::class);
    }

    public function class(){
        return $this->belongsTo(Classes::class, 'class_id');
    }

    function lesson(){
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }

}

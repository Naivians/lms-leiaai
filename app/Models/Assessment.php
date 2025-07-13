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
        'is_published',
    ];

    public function question()
    {
        return $this->hasMany(Question::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
    public function progress()
    {
        return $this->hasMany(AssessmentProgress::class, 'assessment_id');
    }

    public function getAssessmentTimeArrayAttribute()
    {
        $time = $this->assessment_time;
        $hours = 0;
        $minutes = 0;

        preg_match('/(\d+)\s*hrs?/i', $time, $hrMatch);
        preg_match('/(\d+)\s*mins?/i', $time, $minMatch);

        if (!empty($hrMatch[1])) {
            $hours = (int) $hrMatch[1];
        }

        if (!empty($minMatch[1])) {
            $minutes = (int) $minMatch[1];
        }

        return [
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }
}

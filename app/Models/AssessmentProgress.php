<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_id',
        'name',
        'type',
        'total',
        'score',
        'status',
    ];

    public function ProgressDetails(){
        return $this->hasMany(ProgressDetail::class, "progress_id");
    }

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }

    public function assessment(){
        return $this->belongsTo(Assessment::class, "assessment_id");
    }
}

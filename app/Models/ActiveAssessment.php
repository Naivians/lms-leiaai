<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_id',
        'progress_id',
        'page_number',
        'start_time',
        'session_key',
    ];
}

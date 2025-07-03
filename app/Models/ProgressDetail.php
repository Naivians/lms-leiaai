<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'progress_id',
        'qid',
        'cid',
    ];

    public function assessmentProgress(){
        $this->belongsTo(AssessmentProgress::class, 'progress_id');
    }
}

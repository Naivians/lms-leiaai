<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'q_name',
        'assessment_id',
        'type',
    ];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function assessment() {
        return $this->belongsTo(Assessment::class,'assessment_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer',
        'question_id',
        'choice_id',
    ];

    public function choice()
    {
        return $this->belongsTo(Choice::class, 'choices_id');
    }
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

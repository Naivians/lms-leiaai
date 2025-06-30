<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'choices',
        'question_id',
    ];

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answer_keys(){
        return $this->hasMany(AnswerKey::class, 'choice_id');
    }

    public function answer_key(){
        return $this->hasOne(AnswerKey::class, 'choice_id');
    }


}

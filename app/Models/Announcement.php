<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;


    protected $fillable = [
        'class_id',
        'user_id',
        'title',
        'content',
        'announcement_date',
        'is_pinned',
        'attachment',
        'attachment_type',
        'attachment_url',
    ];

    public function class(){
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');

    }
}

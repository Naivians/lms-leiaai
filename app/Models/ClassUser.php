<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classes;

class ClassUser extends Model
{
    use HasFactory;
    // protected $table = 'class_users';

    protected $fillable = [
        'user_id',
        'class_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public static function get_fi_and_cgi($classId)
    {
        return self::where('class_id', $classId)
            ->whereIn('role_id', [1, 2])
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'img', 'created_at', 'role');
            }])
            ->get()
            ->pluck('user')
            ->map(function ($user) {
                $user->role_label = match ($user->role) {
                    1 => 'Flight Instructor',
                    2 => 'Chief Ground Instructor',
                    default => 'unknown',
                };
                return $user;
            });
    }
    public static function get_enrolled_students($classId)
    {
        return self::where('class_id', $classId)
            ->where('role_id', 0)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'img', 'created_at', 'role');
            }])
            ->get()
            ->pluck('user')
            ->map(function ($user) {
                $user->role_label = match ($user->role) {
                    0 => 'Student',
                    default => 'unknown',
                };
                return $user;
            });
    }

    // public static function usersByClass($classId)
    // {
    //     return self::where('class_id', $classId)
    //         ->with(['user' => function ($query) {
    //             $query->select('id', 'name', 'email', 'img', 'role');
    //         }])
    //         ->get()
    //         ->pluck('user');
    // }
}

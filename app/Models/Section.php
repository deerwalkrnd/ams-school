<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
    ];

    protected $fillable = [
        'name',
        'grade_id',
        'user_id',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function getAbsentCount(User $user)
    {
        return $this->getTodayAttendanceStatusCount($user, 'absent');
    }

    public function getPresentCount(User $user)
    {
        return $this->getTodayAttendanceStatusCount($user, 'present');

    }

    public function getTodayAttendanceStatusCount(User $user, string $status)
    {
        $count = Attendance::whereHas('student', function ($query) use ($user) {
            return $query->where('students.section_id', $user->section->id);
        })
            ->whereDate('created_at', date('Y-m-d'))
            ->where('teacher_id', $user->id);

        if ($status == 'present') {
            $count = $count->where('absent', '0')
                ->get()
                ->count();
        } elseif ($status == 'absent') {
            $count = $count->where('absent', '!=', '0')
                ->get()
                ->count();
        }

        return $count;
    }
}

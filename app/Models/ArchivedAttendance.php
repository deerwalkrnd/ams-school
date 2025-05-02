<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'teacher_id',
        'date',
        'present',
        'absent',
        'comment',
    ];
    public function student()
    {
        return $this->belongsTo(ArchivedStudent::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}

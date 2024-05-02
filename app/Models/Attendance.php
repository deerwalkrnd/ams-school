<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'attendances';
    protected $fillable = [
        'student_id',
        'teacher_id',
        'present',
        'absent',
        'comment',
        'date'
    ];


    /**
     * Defines one-to-many relationship between students and  attendance
     *
     * @return void
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Defines one-to-many relationship between teachers and  attendance
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
}

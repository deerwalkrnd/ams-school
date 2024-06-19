<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Old_Attendances extends Model
{
    use HasFactory;
    protected $table = 'old_attendances'; 
    protected $fillable = [
        'student_name',
        'teacher_name',
        'grade_name',
        'section_name',
        'present',
        'absent',
        'date',
    ];
}

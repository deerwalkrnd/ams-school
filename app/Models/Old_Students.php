<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Old_Students extends Model
{
    use HasFactory;
    protected $table = 'old_students'; 
    protected $fillable = [
        'roll_no',
        'name',
        'email',
        'section',
        'grade',
        'status',
       
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedGrade extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
    ];

    public function sections()
    {
        return $this->hasMany(ArchivedSection::class, 'grade_id');
    }
}

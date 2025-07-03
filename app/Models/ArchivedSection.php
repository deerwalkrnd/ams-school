<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_id',
        'user_id',
    ];

    public function students()
    {
        return $this->hasMany(ArchivedStudent::class, 'section_id');
    }

    public function section()
    {
        return $this->belongsTo(ArchivedSection::class);
    }

    public function grade()
    {
        return $this->belongsTo(ArchivedGrade::class, 'grade_id');
    }
}

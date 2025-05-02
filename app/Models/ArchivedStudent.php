<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedStudent extends Model
{
    use HasFactory;
    protected $fillable = [
        'roll_no',
        'name',
        'email',
        'section_id',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(ArchivedSection::class);
    }
}

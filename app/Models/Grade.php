<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'name',
        'start_date',
        'end_date'
    ];

    protected $dates = ['end_date'];

    public function section(){
        return $this->hasMany(Section::class);
    }

    public function forceDeleteRecord()
    {
        if ($this->trashed()) {
            $this->forceDelete();
            return true;
        }
        return false;
    }

    protected static function booted()
    {
        static::addGlobalScope('active', function ($builder) {
            $builder->where('end_date', '>=', now());
        });
    }
}

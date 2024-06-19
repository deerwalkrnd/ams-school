<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function section()
    {
        return $this->hasOne(Section::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Section::class);
    }

    /**
     * Defines one-to-many relationship between teachers and attendance
     *
     * @return void
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_id', 'id');
    }

    /**
     * Check if user has a given role
     *
     * @param  string $role
     * @return void
     */
    public function hasRole(string $role)
    {

        if ($this->roles()->where('role', $role)->first()) {
            return true;
        }

        return false;
    }

    /**
     * Get all the attendances date
     *
     * Set a limit to the number of attendance dates to get
     * @param integer $limit
     * @param date $startDate
     * @param date $endDate
     * 
     * @return mixed
     */
    public function getAllAttendanceDates($startDate, $endDate, $limit = 50)
    {
        $startDate = $startDate ?? Auth::user()->section->grade->start_date;
        $endDate = $endDate ?? date('Y-m-d');

        $attendance = $this->attendances
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(function ($query) {
                return Carbon::parse($query->date)->format('m/d');
            })
            ->take($limit);

        return $attendance->keys();
    }

    /**
     * Get total classes count
     * @param date $startDate
     * @param date $endDate
     *
     * @return mixed
     */
    public function getTotalClasses($startDate, $endDate)
    {
        $startDate = $startDate ?? Auth::user()->section->grade->start_date;
        $endDate = $endDate ?? date('Y-m-d');
        return $this->attendances->whereBetween('date', [$startDate, $endDate])->count() ?? "-";
    }
}

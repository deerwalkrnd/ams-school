<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',

    ];

    protected $fillable = [
        'roll_no',
        'name',
        'email',
        'section_id',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Defines one-to-many relationship between student and attendance
     *
     * @return void
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAbsentDays($id)
    {
        $attendances = Attendance::where('student_id', $this->id)
            ->where('attendances.teacher_id', $id)
            ->where('created_at', '>', Carbon::now()->subDays(6))
            ->sum('absent');

        return $attendances;
    }

    /**
     * Get the attendances of a studemt
     *
     * Set a limit to the number of attendances to get
     *
     * @param  int  $limit
     * @param  date  $startDate
     * @param  date  $endDate
     * @return mixed
     */
    public function getAttendances($startDate, $endDate)
    {
        $startDate = $startDate ?? Auth::user()->section->grade->start_date;
        $endDate = $endDate ?? Carbon::today()->addDay()->format('Y-m-d');

        $attendance = $this->attendances
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(function ($query) {
                return Carbon::parse($query->date)->format('m/d');
            })
            ->map(function ($attendance) {
                $temp['present'] = $attendance->first()->present;
                $temp['absent'] = $attendance->first()->absent;
                $temp['comment'] = $attendance->first()->comment;

                return $temp;
            });
        // ->toArray();
        // dd($attendance);

        return $attendance;
    }

    public function attendanceByDate($date)
    {
        return $this->attendances()
            ->whereDate('created_at', $date)
            ->first();
    }
}

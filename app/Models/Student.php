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
     * @param integer $limit
     * @param date $startDate
     * @param date $endDate
     * @return mixed
     */
    public function getAttendances($startDate, $endDate, $limit = 30)
{
    $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
    $startDate = $startDate ? Carbon::parse($startDate) : $endDate->copy()->subDays(60);

    $sectionDates = $this->section->getAllAttendanceDates($startDate, $endDate, $limit)->pluck('date');
// dd($sectionDates);
    $attendanceRecords = $this->attendances
        ->where('date', '>=', $startDate->subDay(1))
        ->where('date', '<=', $endDate)
        ->sortByDesc('date')
        ->groupBy(function ($query) {
            return Carbon::parse($query->date)->format('Y-m-d');
        });
// dd($attendanceRecords);
    $attendance = $sectionDates->mapWithKeys(function ($date) use ($attendanceRecords) {
        if ($attendanceRecords->has($date)) {
            $attendanceForDate = $attendanceRecords->get($date)->first();
            
            return [
                $date => [
                    'present' => $attendanceForDate->present,
                    'absent' => $attendanceForDate->absent,
                    'comment' => !empty($attendanceForDate->comment) ? $attendanceForDate->comment : 'No Comments',
                ],
            ];
        } else {
            return [
                $date => [
                    'present' => 0,
                    'absent' => 0,
                    'comment' => '',
                ],
            ];
        }
    })
    ->take($limit)
    ->sortKeys();
    // dd($attendance);
    return $attendance;
}

    public function getAttendanceDates($startDate, $endDate, $limit = 30)
    {
        $startDate = $startDate ?? $this->section->grade->start_date;
        $endDate = $endDate ?? Carbon::today()->addDay()->format("Y-m-d");

        $getAttendanceDates = $this->attendances
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('date')
            ->unique()
            ->sortBy('date')
            ->take($limit)
            ->values();

        return $getAttendanceDates;
    }
}

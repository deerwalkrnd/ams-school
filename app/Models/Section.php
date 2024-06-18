<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
    ];
    protected $fillable = [
        'name',
        'grade_id',
        'user_id',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function getAbsentCount(User $user)
    {
        return $this->getTodayAttendanceStatusCount($user, "absent");
    }

    public function getPresentCount(User $user)
    {
        return $this->getTodayAttendanceStatusCount($user, "present");

    }

    public function getTodayAttendanceStatusCount(User $user, string $status)
    {
        $count = Attendance::whereHas('student', function($query) use($user){
                        return $query->where('students.section_id', $user->section->id);
                            })
                            ->whereDate("created_at", date('Y-m-d'))
                            ->where('teacher_id', $user->id);

        if ($status == 'present') {
            $count = $count->where('absent', '0')
                ->get()
                ->count();
        }
        elseif ($status == 'absent') {
            $count = $count->where('absent', '!=', '0')
                ->get()
                ->count();
        }
        return $count;
    }
    public function getAllAttendanceDates($startDate, $endDate, $limit=50)
    {
        // $startDate = $startDate ?? Auth::user()->section->grade->start_date;
    //    dd($startDate);
        $startDate=$startDate ?? $this->grade->start_date;
        $endDate = $endDate ?? date('Y-m-d');
        // dd($startDate,$endDate);
        
        // $attendance = $this->attendances
        //                     ->whereBetween('date', [$startDate, $endDate])
        //                     ->groupBy(function ($query) {
        //                         return Carbon::parse($query->date)->format('m/d');
        //                     })
        //                     ->take($limit);

        // $attendances = $this->student()
        // ->with('attendances')
        // ->whereHas('attendances', function ($query) use ($startDate, $endDate) {
        //     $query->when($startDate, function ($query) use ($startDate) {
        //         $query->whereDate('date', '>=', $startDate);
        //     });
        //     $query->when($endDate, function ($query) use ($endDate) {
        //         $query->whereDate('date', '<=', $endDate);
        //     });
           
        // })
        // ->get()
        // ->pluck('attendances')
        // ->collapse()
        // ->unique('date')
        // ->values()
        // ->sortBy('date')
        // ->take($limit);
        // ;

$student= $this->student()->where('status', 'active')->first();
// dd($student);
$attendances=$student->attendances;
// $attendances=$attendances->where('date', '>=', $startDate)->where('date', '<=' , $endDate);
$attendances=$attendances->where('date', '>=', $startDate)->where('date', '<=' , $endDate);
// dd($attendances);
// dd($attendances);

        // $attendances=$this->student()->with('attendances')->whereHas('attendances', function ($query) use ($startDate, $endDate) {
        //     return $query->whereBetween('date', [$startDate, $endDate]);
        // });
        // dd($attendances->pluck('date'));
        
    return $attendances;
            // dd($this->student->whereHas('student', function ($query) use ($sectionId){
            //     return $query->where('section_id', $sectionId);
            // }));
        

        // return $attendance->keys();
    }
    public function getTotalClasses($startDate, $endDate)
    {
        $startDate = $startDate ?? $this->grade->start_date;
        $endDate = $endDate ?? date('Y-m-d');
        // dd($endDate);
        // dd($startDate,$endDate);
$student= $this->student()->where('status', 'active')->first();
$attendances=$student->attendances;
// dd($student);
// $attendances = $student->attendances->map(function ($attendance) {
//     $attendance->date = Carbon::parse($attendance->date);
//     return $attendance;
// });

// dd($attendances);

// dd($attendances->date);
$attendances->whereBetween('date', [$startDate, $endDate]);
// dd($attendances);

        
        return $attendances->whereBetween('date', [$startDate, $endDate])->count() ?? "-";
    }
}

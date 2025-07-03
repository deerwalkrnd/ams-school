<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class UsusalAttendanceReportExport implements FromView
{
    use Exportable;

    private $students;

    private $startDate;

    private $endDate;

    private $attendanceDates;

    private $teacher;

    public function __construct(Collection $students, $attendanceDates, ?string $startDate = null, ?string $endDate = null, ?User $teacher = null)
    {
        $this->students = $students;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->attendanceDates = $attendanceDates;
        $this->teacher = $teacher ?? Auth::user();
    }

    public function view(): View
    {
        $students = $this->students;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $attendanceDates = $this->attendanceDates;
        $teacher = $this->teacher;

        return view('layouts.exports.usualReport', compact('students', 'startDate', 'endDate', 'attendanceDates', 'teacher'));

    }
}

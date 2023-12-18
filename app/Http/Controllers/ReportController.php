<?php

namespace App\Http\Controllers;

use App\Exports\UsusalAttendanceReportExport;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function adminIndex()
    {
        //get latest attendance
        $latestAttendance = Attendance::latest()->first();
        $startDate = $latestAttendance->user->section->grade->start_date;
        $attendanceDates = User::where('id', $latestAttendance->teacher_id)->first()->getAllAttendanceDates($startDate, null);
        $students = Student::where('section_id', $latestAttendance->user->section->id)->get()->sortBy('roll_no');
        $grades = Grade::all()->sortBy('name');
        $teacher = $latestAttendance->user;
        return view('admin.report.index', compact('attendanceDates', 'students', 'startDate', 'grades', 'teacher'));
    }

    public function adminSearch(Request $request)
    {
        $startDate = null;
        $endDate = null;

        $teacher = Section::where('id', $request->grade)->first()->user;
        $grades = Grade::all()->sortBy('name');
        $students = Student::where('section_id',  $request->grade);


        if ($request->has('student')) {
            $students = $students->where('roll_no', $request->student);
        }


        if ($request->has('start_date')) {
            $startDate = $request->start_date;
        }

        if ($request->has('end_date')) {
            $endDate = $request->end_date;
        }

        $students = $students->get()
            ->sortBy('roll_no');


        $startDate = $startDate ?? $teacher->section->grade->start_date;

        $attendanceDates = $teacher->getAllAttendanceDates($startDate, $endDate);

        return view('admin.report.index', compact('students', 'startDate', 'endDate', 'attendanceDates', 'grades', 'teacher'));
    }

    public function gradeSearch(Request $request)
    {
        $startDate = Section::where('id', $request->grade)->first()->grade->start_date;
        $students = Student::where('section_id', $request->grade)->pluck('name', 'roll_no');

        return response()->json(['students' => $students, 'start_date' => $startDate]);
    }


    public function adminReportDownload(Request $request)
    {
        $teacher = Section::where('id', $request->grade)->first()->user;
        $students = Student::where('section_id', $request->grade);
        $startDate = null;
        $endDate = null;

        if ($request->student != "false") {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->start_date != null && $request->start_date != "false") {
            $startDate = $request->start_date;
        }

        if ($request->end_date != null && $request->end_date != "false") {
            $endDate = $request->end_date;
        }

        $students = $students->get()->sortBy('roll_no');
        $startDate = $startDate ?? $teacher->section->grade->start_date;
        $attendanceDates = $teacher->getAllAttendanceDates($startDate, $endDate);
        return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate, $teacher))->download(time() . '.xlsx');

    }

    public function teacherIndex()
    {
        $attendanceDates = Auth::user()->getAllAttendanceDates(null, null);
        $students = Auth::user()->students()->get()->sortBy('roll_no');
        return view('teacher.report.index', compact('attendanceDates', 'students'));
    }

    public function teacherSearch(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $students = Student::where('section_id', Auth::user()->section->id);

        if ($request->has('student')) {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->has('start_date')) {
            $startDate = $request->start_date;
        }

        if ($request->has('end_date')) {
            $endDate = $request->end_date;
        }


        $students = $students->get()->sortBy('roll_no');

        $attendanceDates = Auth::user()->getAllAttendanceDates($startDate, $endDate);

        return view('teacher.report.index', compact('students', 'startDate', 'endDate', 'attendanceDates'));
    }

    public function teacherReportDownload(Request $request)
    {

        $students = Student::where('section_id', Auth::user()->section->id);
        $startDate = null;
        $endDate = null;

        if ($request->student != "false") {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->start_date != null && $request->start_date != "false") {
            $startDate = $request->start_date;
        }

        if ($request->end_date != null && $request->end_date != "false") {
            $endDate = $request->end_date;
        }

        $students = $students->get()->sortBy('roll_no');
        $attendanceDates = Auth::user()->getAllAttendanceDates($startDate, $endDate);
        return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate))->download(time() . '.xlsx');
    }
}

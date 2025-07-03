<?php

namespace App\Http\Controllers;

use App\Exports\UsusalAttendanceReportExport;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function adminIndex()
    {
        try {
            //get latest attendance
            $latestAttendance = Attendance::latest()->first();
            $startDate = $latestAttendance->user->section->grade->start_date;
            $attendanceDates = User::where('id', $latestAttendance->teacher_id)->first()->getAllAttendanceDates($startDate, null);
            $students = Student::where('section_id', $latestAttendance->user->section->id)->get()->sortBy('roll_no');
            $grades = Grade::all()->sortBy('name');
            $teacher = $latestAttendance->user;

            return view('admin.report.index', compact('attendanceDates', 'students', 'startDate', 'grades', 'teacher'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching admin report index: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function adminSearch(Request $request)
    {
        try {
            $startDate = null;
            $endDate = null;

            $teacher = Section::where('id', $request->grade)->first()->user;
            $grades = Grade::all()->sortBy('name');
            $students = Student::where('section_id', $request->grade);

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
        } catch (Exception $e) {
            Log::error('Error occurred while searching admin report: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function gradeSearch(Request $request)
    {
        try {
            $startDate = Section::where('id', $request->grade)->first()->grade->start_date;
            $students = Student::where('section_id', $request->grade)->pluck('name', 'roll_no');

            return response()->json(['students' => $students, 'start_date' => $startDate]);
        } catch (Exception $e) {
            Log::error('Error occurred while searching grade: '.$e->getMessage());

            return response()->json(['error' => 'Oops! Error Occurred. Please Try Again Later.'], 500);
        }
    }

    public function adminReportDownload(Request $request)
    {
        try {
            $teacher = Section::where('id', $request->grade)->first()->user;
            $section_name = Section::where('id', $request->grade)->first()->name;
            $grade_name = Section::where('id', $request->grade)->first()->grade->name;
            $students = Student::where('section_id', $request->grade);
            $startDate = null;
            $endDate = null;

            if ($request->student != 'false') {
                $students = $students->where('roll_no', $request->student);
            }

            if ($request->start_date != null && $request->start_date != 'false') {
                $startDate = $request->start_date;
            }

            if ($request->end_date != null && $request->end_date != 'false') {
                $endDate = $request->end_date;
            }

            $students = $students->get()->sortBy('roll_no');
            $startDate = $startDate ?? $teacher->section->grade->start_date;
            $attendanceDates = $teacher->getAllAttendanceDates($startDate, $endDate);

            // dd($attendanceDates, $students, $startDate, $endDate, $teacher);
            return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate, $teacher))->download($grade_name.'_'.$section_name.'_'.time().'.xlsx');
        } catch (Exception $e) {
            Log::error('Error occurred while downloading admin report: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function teacherIndex()
    {
        try {
            $attendanceDates = Auth::user()->getAllAttendanceDates(null, null);
            $students = Auth::user()->students()->get()->sortBy('roll_no');

            return view('teacher.report.index', compact('attendanceDates', 'students'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching teacher report index: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function teacherSearch(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Error occurred while searching teacher report: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    public function teacherReportDownload(Request $request)
    {

        try {
            $students = Student::where('section_id', Auth::user()->section->id);
            $startDate = null;
            $endDate = null;

            if ($request->student != 'false') {
                $students = $students->where('roll_no', $request->student);
            }

            if ($request->start_date != null && $request->start_date != 'false') {
                $startDate = $request->start_date;
            }

            if ($request->end_date != null && $request->end_date != 'false') {
                $endDate = $request->end_date;
            }

            $students = $students->get()->sortBy('roll_no');
            $attendanceDates = Auth::user()->getAllAttendanceDates($startDate, $endDate);

            return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate))->download(time().'.xlsx');
        } catch (Exception $e) {
            Log::error('Error occurred while downloading teacher report: '.$e->getMessage());

            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
}

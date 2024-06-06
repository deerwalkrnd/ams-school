<?php

namespace App\Http\Controllers;

use App\Exports\UsusalAttendanceReportExport;
use App\Models\Attendance;
use App\Models\Grade;
use Exception;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class ReportController extends Controller
{
    public function adminIndex()
    {
    //    try{ //get latest attendance
        $latestAttendance = Attendance::latest()->first();
        $startDate = $latestAttendance->student->section->grade->start_date;
        $attendanceDates = 
            Section::where('grade_id',$latestAttendance->student->section->grade->id)
        ->first()->getAllAttendanceDates($startDate, null);
        // dd($attendanceDates);
        $students = Student::where('status', 'active')->where('section_id', $latestAttendance->user->section->id)->get()->sortBy('roll_no');
        // dd($students);
        $grades = Grade::all()->sortBy('name');
        $teacher = $latestAttendance->user;
        
        return view('admin.report.index', compact('attendanceDates', 'students', 'startDate', 'grades', 'teacher'));
    // }catch(Exception $e) {
    //     Log::error($e->getMessage());  
    //     return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    // }
    }

    public function adminSearch(Request $request)
    {
        // try{
        $startDate = null;
        $endDate = null;
            // dd($request->all());
        // $teacher = Section::where('id', $request->grade)->first()->user;
        $grades = Grade::all()->sortBy('name');
        $students = Student::where('section_id',  $request->grade);
        $section=Section::where('id', $request->grade)->first();
        // dd($section);
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

        // dd($section);
        $startDate = $startDate ?? $section->grade->start_date;
        $attendanceDates = $section->getAllAttendanceDates($startDate, $endDate);
        
        // dd($attendanceDates);
        return view('admin.report.index', compact('students', 'startDate', 'endDate', 'attendanceDates', 'grades', ));
    // }catch(Exception $e) {
    //     Log::error($e->getMessage());  
    //     return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    // }
    }

    public function gradeSearch(Request $request)
    {
        try{
            
        $startDate = Section::where('id', $request->grade)->first()->grade->start_date;
        $students = Student::where('section_id', $request->grade)->pluck('name', 'roll_no');
        return response()->json(['students' => $students, 'start_date' => $startDate]);
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }


    public function adminReportDownload(Request $request)
    {
        try{$teacher = Section::where('id', $request->grade)->first()->user;
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
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }

    }

    public function teacherIndex()
    {
       try{ $attendanceDates = Auth::user()->getAllAttendanceDates(null, null);
        $students = Auth::user()->students()->get()->sortBy('roll_no');
        return view('teacher.report.index', compact('attendanceDates', 'students'));}
        catch(Exception $e){
            return redirect()->back()->with('error', "Teacher not assigned yet");
        }
    }

    public function teacherSearch(Request $request)
    {
        try{$startDate = null;
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
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function teacherReportDownload(Request $request)
    {
        try{
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
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
}

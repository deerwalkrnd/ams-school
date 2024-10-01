<?php

namespace App\Http\Controllers;

use App\Exports\UsusalAttendanceReportExport;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;
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
       try{
        $latestAttendance = Attendance::latest()->first();
           $endDate= Carbon::now();
        $startDate=$endDate->copy()->subDays(60);
        $attendanceDates = 
            Section::where('id',$latestAttendance->student->section->id)
        ->first()->getAllAttendanceDates($startDate, $endDate,30);
        // dd($attendanceDates);
        $students = Student::where('status', 'active')->where('section_id', $latestAttendance->student->section->id)->get()->sortBy('roll_no');
        // dd($students);
        $grades = Grade::all()->sortBy('name');
        $teacher = $latestAttendance->user;
        $section=Section::where('grade_id',$latestAttendance->student->section->grade->id)
        ->first();
        return view('admin.report.index', compact('attendanceDates', 'students', 'startDate', 'grades', 'teacher', 'section'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function adminSearch(Request $request)
    {
        try{
        $startDate = null;
        $endDate = null;
        $limit=30;
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
        if($startDate!=null||$endDate!=null){
            $limit=365;
        }
        if(!$section){
            return redirect()->back()->withErrors("Please select a section");
        }
        $attendanceDates = $section->getAllAttendanceDates($startDate, $endDate,$limit);
        
        // dd($attendanceDates->pluck('date'));
        return view('admin.report.index', compact('students', 'startDate', 'endDate', 'attendanceDates', 'grades', 'limit'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function gradeSearch(Request $request)
    {
        try{
            
        $startDate = Section::where('id', $request->grade)->first()->grade->start_date;
        $students = Student::where('section_id', $request->grade)->pluck('name', 'roll_no');
        return response()->json(['students' => $students, 'start_date' => $startDate]);
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }


    public function adminReportDownload(Request $request)
    {
        try{$teacher = Section::where('id', $request->grade)->first()->user;
        $students = Student::where('section_id', $request->grade);
        $startDate = null;
        $endDate = null;
        $section=Section::where('id', $request->grade)->first();
        $limit=365;
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
        $startDate = $startDate ?? Carbon::now()->subDays(60);
        // dd($startDate,$endDate);
        // dd($limit);
        $attendanceDates = $section->getAllAttendanceDates($startDate, $endDate,$limit);
        // dd($attendanceDates->pluck('date'));
        return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate, $teacher))->download(time() . '.xlsx');
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }

    }

    public function teacherIndex()
    {
       try{  $latestAttendance = Attendance::where('teacher_id',Auth::user()->id)->latest()->first();
        // dd($latestAttendance);
        $endDate= Carbon::now();
        $startDate=$endDate->copy()->subDays(60);
        $attendanceDates = 
        Section::where('id',$latestAttendance->student->section->id)
    ->first()->getAllAttendanceDates($startDate, null,30);

    $section= Section::where('id', Auth::user()->section->id)->first();
        $students = Auth::user()->students()->get()->sortBy('roll_no');
        
        return view('teacher.report.index', compact('attendanceDates', 'students','section', 'startDate'));}
        catch(Exception $e){
            return redirect()->back()->with('error', "Teacher not assigned yet");
        }
    }

    public function teacherSearch(Request $request)
    {
        try{
            $startDate = null;
        $endDate = null;
        $students = Student::where('section_id', Auth::user()->section->id);
        $limit=30;
        // dd($students);
            $section= Section::where('id', Auth::user()->section->id)->first();
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
        if($startDate!=null||$endDate!=null){
            // dd("here");
            $limit=365;
        }
        
        // dd($students->get());
        $students = $students->get()->sortBy('roll_no');
        $attendanceDates = $section->getAllAttendanceDates($startDate, $endDate,$limit);
        // dd($attendanceDates);
        return view('teacher.report.index', compact('students', 'startDate', 'endDate', 'attendanceDates',  'section','limit'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }

    public function teacherReportDownload(Request $request)
    {
        try{
        $students = Student::where('section_id', Auth::user()->section->id);
        $startDate = null;
        $endDate = null;
        $section=  Section::where('id', Auth::user()->section->id)->first();
        $limit=30;
        if ($request->student != "false") {
            $students = $students->where('roll_no', $request->student);
        }

        if ($request->start_date != null && $request->start_date != "false") {
            $startDate = $request->start_date;
        }

        if ($request->end_date != null && $request->end_date != "false") {
            $endDate = $request->end_date;
        }
        // dd($section);
        if($startDate!=null||$endDate!=null){
            $limit=365;
        }
        $startDate = $startDate ?? Carbon::now()->subDays(30);
        $students = $students->get()->sortBy('roll_no');
        $attendanceDates = $section->getAllAttendanceDates($startDate, $endDate,$limit);
        return (new UsusalAttendanceReportExport($students, $attendanceDates, $startDate, $endDate))->download(time() . '.xlsx');
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('Oops! Error Occured. Please Try Again Later.');   
    }
    }
}

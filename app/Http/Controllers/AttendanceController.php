<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;

use App\Models\Student;
use App\Models\Attendance;
use App\Http\Requests\AttendanceRequest;
use App\Models\Grade;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       try{ $attendances = Attendance::where('date', date('Y-m-d'))
        ->get()
        ->groupBy('teacher_id');

    $userIds = $attendances->keys();

    $users = User::with(['section.grade'])
        ->whereIn('id', $userIds)
        ->get();

        $users = $users->sortBy(function ($user) {
            if ($user->section && $user->section->grade) {
                return $user->section->grade->name;
            } else {
                return null;
            }
        });
        return view('admin.attendance.index', compact('users'));}
        catch(Exception $e) {
            Log::error($e->getMessage());  
            return redirect()->back()->withErrors('error','Failed to get attendance');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    
       try{ $attendanceDates = Attendance::where('teacher_id', Auth::user()->id)
        ->where('created_at', '>', Carbon::now()->subDays(6))
        ->get()
        ->groupBy(function ($query) {
            return Carbon::parse($query->created_at)->format('Y-m-d');
        })
        ->take(5);

    $students = Student::where('status', 'active')->get();
    $attendanceDates = $attendanceDates->filter(function ($date) use ($students) {
        return $students->contains('id', $date->first()->student_id);
    });
    return view('teacher.attendance.index', compact("attendanceDates"));
        }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceRequest $request)
    {
        $input = $request->validated();       
        try {
            DB::beginTransaction();
            $user = Auth::user();
            foreach ($input['attendances'] as $attendanceAndRoll) {
                $student = Student::where('roll_no', $attendanceAndRoll['rollNo'])->first();
                $attendance = new Attendance();
                $attendance->student_id = $student->id;
                $attendance->teacher_id =$student->section->user->id??Auth::user()->id;
                $attendance->present = $attendanceAndRoll['attendanceStatus']['present'];
                $attendance->absent = $attendanceAndRoll['attendanceStatus']['absent'];
                // Handle comments for absent students
                if ($attendance->absent) {
                    $comment = isset($attendanceAndRoll['attendanceStatus']['comment']) ? $attendanceAndRoll['attendanceStatus']['comment'] : '';
                    $attendance->comment = $comment;
                }
                $attendance->date = date('Y-m-d');
                $attendance->save();
            }  
            DB::commit();            
            return response()->json(['msg' => 'Attendance Has Been Taken Successfully!', 200]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while uploading attendance.' .  $e);
            return response()->json(['msg' => 'Oops! Error Occured. Please Try Again Later.', 400]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try{$attendances = Attendance::with('student')
            ->where('teacher_id', $user->id)
            ->where('date', date('Y-m-d'))
            ->get()
            ->sortBy('student.roll_no');

        return view('admin.attendance.edit', compact('attendances', 'user'));
    }catch(Exception $e) {
            Log::error($e->getMessage());  
            return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceRequest $request, User $user)
    {
        $input = $request->validated();
        try {
            DB::beginTransaction();
            foreach ($input['attendances'] as $attendanceAndRoll) {
                $student = Student::where('roll_no', $attendanceAndRoll['rollNo'])
                ->where('status', 'active')
                ->orderBy('name')
                ->first();
                $attendance = Attendance::where('teacher_id', $user->id)
                    ->where('student_id', $student->id)
                    ->where('date', date('Y-m-d'))
                    ->first();

                $attendance->present = $attendanceAndRoll['attendanceStatus']['present'];
                $attendance->absent = $attendanceAndRoll['attendanceStatus']['absent'];
                // Handle comments for absent students
                if ($attendanceAndRoll['attendanceStatus']['absent'] > 0) {
                    $comment = isset($attendanceAndRoll['attendanceStatus']['comment']) ? $attendanceAndRoll['attendanceStatus']['comment'] : '';
                    $attendance->comment = $comment;
                } else {
                    $attendance->comment = "";
                }
                $attendance->save();
            }
            DB::commit();
            return response()->json(['msg' => 'Attendance Has Been Updated Successfully!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error occured while updating attendance.' .  $e);
            return response()->json(['msg' => 'Oops! Error Occured. Please Try Again Later.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function adminAttendanceIndex(Request $request)
    {
        // dd($request->all());
        try{$sections = Section::with('grade')->get();
        if ($request->has('section')) {

            $students = Student::where('section_id', $request->section)
            ->where('status', 'active')
                ->orderBy('roll_no')->get();
            $checkIfTodayAttendanceExists =  Attendance::whereHas('student', function ($query) use ($request) {
                return $query->where('students.section_id', $request->section);
            })
                ->whereDate("created_at", date('Y-m-d'))
                ->count();

            if ($checkIfTodayAttendanceExists) {
                return redirect()->route('attendance.takeAttendance')->with('error', "Attendance for the grade on today's date already exist");
            }

            return view('admin.attendance.adminAttendance', compact('sections', 'students'));
        }

        return view('admin.attendance.adminAttendance', compact('sections'));
    }catch(Exception $e) {
        Log::error($e->getMessage());  
        return redirect()->back()->withErrors('error','Oops! Error Occured. Please Try Again Later.');   
    }
    }
}

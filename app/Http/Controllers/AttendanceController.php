<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;

use App\Models\Student;
use App\Models\Attendance;
use App\Http\Requests\AttendanceRequest;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attendanceDates = Attendance::where('teacher_id', Auth::user()->id)
            ->where('created_at', '>', Carbon::now()->subDays(6))
            ->get()
            ->groupBy(function ($query) {
                return Carbon::parse($query->created_at)->format('M/d');
            })
            ->take(5);

        return view('teacher.attendance.index', compact("attendanceDates"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceRequest $request)
    {
        $input = $request->validated();

        try {
            DB::beginTransaction();

            foreach ($input['attendances'] as $attendanceAndRoll) {
                $student = Student::where('roll_no', $attendanceAndRoll['rollNo'])->first();
                $attendance = new Attendance();
                $attendance->student_id = $student->id;
                $attendance->teacher_id = Auth::user()->id;
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
            Log::info('Request data received:', ['request_data' => $request->all()]);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

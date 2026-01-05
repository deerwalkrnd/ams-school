<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Exception;
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
        try {
            $attendances = Attendance::where('date', date('Y-m-d'))
                ->get()
                ->groupBy('teacher_id');
            $users = User::with('section')->whereIn('id', $attendances->keys())->get();
            $users = $users->sortBy('section.grade.name');

            // Get attendance status for all teachers
            $attendanceStatuses = AttendanceStatus::where('date', date('Y-m-d'))
                ->with('teacher.section.grade')
                ->get()
                ->keyBy('teacher_id');

            return view('admin.attendance.index', compact('users', 'attendanceStatuses'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching attendance index: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $attendanceDates = Attendance::where('teacher_id', auth()->id())
                ->whereDate('created_at', '>=', now()->subDays(6))
                ->orderBy('created_at')
                ->get()
                ->groupBy(fn($a) => $a->created_at->format('Y-m-d'))
                ->take(5);

            $todayTaken = $attendanceDates->has(now()->toDateString());

            $minDate = $attendanceDates->isNotEmpty()
                ? now()->subDays(6)->toDateString()
                : null;

            $maxDate = now()->toDateString();

            return view('teacher.attendance.index', compact(
                'attendanceDates',
                'minDate',
                'maxDate',
                'todayTaken'
            ));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Something went wrong.');
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

            $teacherId = $request->teacher ?? Auth::user()->id;
            $today = date('Y-m-d');

            foreach ($input['attendances'] as $attendanceAndRoll) {
                $student = Student::where('roll_no', $attendanceAndRoll['rollNo'])->first();
                $attendance = new Attendance();
                $attendance->student_id = $student->id;
                $attendance->teacher_id = $teacherId;
                $attendance->present = $attendanceAndRoll['attendanceStatus']['present'];
                $attendance->absent = $attendanceAndRoll['attendanceStatus']['absent'];

                // Handle comments for absent students
                if ($attendance->absent) {
                    $comment = isset($attendanceAndRoll['attendanceStatus']['comment']) ? $attendanceAndRoll['attendanceStatus']['comment'] : '';
                    $attendance->comment = $comment;
                }

                $attendance->date = $today;
                $attendance->save();
            }

            // Update or create attendance status
            AttendanceStatus::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'date' => $today,
                ],
                [
                    'status' => 1, // Mark as taken
                ]
            );

            DB::commit();
            return response()->json(['msg' => 'Attendance Has Been Taken Successfully!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while uploading attendance.' . $e);
            return response()->json(['msg' => 'Oops! Error Occurred. Please Try Again Later.'], 400);
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
        try {
            $attendances = Attendance::with('student')
                ->where('teacher_id', $user->id)
                ->where('date', date('Y-m-d'))
                ->get()
                ->sortBy('student.roll_no');

            return view('admin.attendance.edit', compact('attendances', 'user'));
        } catch (Exception $e) {
            Log::error('Error occurred while editing attendance: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
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
                $student = Student::where('roll_no', $attendanceAndRoll['rollNo'])->first();
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
                    $attendance->comment = '';
                }

                $attendance->save();
            }

            // Ensure attendance status is marked as taken
            AttendanceStatus::updateOrCreate(
                [
                    'teacher_id' => $user->id,
                    'date' => date('Y-m-d'),
                ],
                [
                    'status' => 1, // Mark as taken
                ]
            );

            DB::commit();
            return response()->json(['msg' => 'Attendance Has Been Updated Successfully!'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while updating attendance.' . $e);
            return response()->json(['msg' => 'Oops! Error Occurred. Please Try Again Later.'], 400);
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
        try {
            $sections = Section::with('grade')->get();
            if ($request->has('section')) {
                $students = Student::where('section_id', $request->section)->get();
                $checkIfTodayAttendanceExists = Attendance::whereHas('student', function ($query) use ($request) {
                    return $query->where('students.section_id', $request->section);
                })
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count();

                if ($checkIfTodayAttendanceExists) {
                    return redirect()->route('attendance.takeAttendance')->with('error', "Attendance for the grade on today's date already exist");
                }

                return view('admin.attendance.adminAttendance', compact('sections', 'students'));
            }

            return view('admin.attendance.adminAttendance', compact('sections'));
        } catch (Exception $e) {
            Log::error('Error occurred while taking attendance: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }

    /**
     * Get attendance status dashboard for admin
     */
    public function attendanceStatusDashboard()
    {
        try {
            $today = date('Y-m-d');

            // Get all teachers with their attendance status
            $teachers = User::whereHas('roles', function ($query) {
                $query->where('role', 'teacher');
            })->with(['section.grade'])->get();

            $attendanceStatuses = AttendanceStatus::where('date', $today)
                ->get()
                ->keyBy('teacher_id');

            // Prepare data for the view
            $teacherStatuses = $teachers->map(function ($teacher) use ($attendanceStatuses) {
                $status = $attendanceStatuses->get($teacher->id);
                return [
                    'teacher' => $teacher,
                    'status' => $status ? $status->status : 0,
                    'reminder_sent' => $status ? $status->reminderSent() : false,
                    'reminder_sent_at' => $status ? $status->reminder_sent_at : null,
                ];
            });

            return view('admin.attendance.status-dashboard', compact('teacherStatuses', 'today'));
        } catch (Exception $e) {
            Log::error('Error occurred while fetching attendance status dashboard: ' . $e->getMessage());
            return back()->with('error', 'Oops! Error Occurred. Please Try Again Later.');
        }
    }
}

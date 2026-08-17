<?php

namespace App\Console\Commands;

use App\Mail\AdminAttendanceSummaryMail;
use App\Models\AttendanceStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckAttendanceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check-reminders {--force : Force send reminders even if already sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for teachers who haven\'t taken attendance and send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        $force = $this->option('force');
        $this->info("Checking attendance reminders for {$today}");

        // Get all teachers
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->with('section.grade')->get();

        $pendingTeachersForAdminSummary = collect();

        $admins = User::whereHas('roles', function ($query) {
            $query->where('role', 'admin');
        })->get();

        foreach ($teachers as $teacher) {
            // Check if attendance status exists for today
            $attendanceStatus = AttendanceStatus::where('teacher_id', $teacher->id)
                ->where('date', $today)
                ->first();

            // If no record exists, create one with status 0 (not taken)
            if (!$attendanceStatus) {
                $attendanceStatus = AttendanceStatus::create([
                    'teacher_id' => $teacher->id,
                    'date' => $today,
                    'status' => 0,
                ]);
            }

            // If attendance not taken and reminder not sent (or force option is used)
            if ($attendanceStatus->status == 0 && (! $attendanceStatus->reminderSent() || $force)) {
                $pendingTeachersForAdminSummary->push($teacher);

                $attendanceStatus->update([
                    'reminder_sent_at' => now(),
                ]);
            }
        }

        // Send consolidated email to admins if there are pending teachers
        if ($pendingTeachersForAdminSummary->isNotEmpty()) {
            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new AdminAttendanceSummaryMail($pendingTeachersForAdminSummary, $today));
                    $this->info("Consolidated attendance summary sent to admin {$admin->name} ({$admin->email})");
                } catch (\Exception $e) {
                    Log::error("Failed to send consolidated attendance summary to admin {$admin->email}: " . $e->getMessage());
                    $this->error("Failed to send consolidated summary to admin {$admin->name}");
                }
            }
        } else {
            $this->info("All teachers have taken attendance. No consolidated summary sent to admins.");
        }

        $this->info("Teacher reminder emails skipped (teachers use ARMS 360). Pending teachers tracked: {$pendingTeachersForAdminSummary->count()}");
        Log::info("Attendance reminders check completed. Teacher emails skipped. Admin summary sent for {$pendingTeachersForAdminSummary->count()} pending teachers.");

        return 0;
    }
}
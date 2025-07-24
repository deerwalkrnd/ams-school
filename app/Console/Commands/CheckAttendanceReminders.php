<?php

namespace App\Console\Commands;

use App\Mail\AttendanceReminderMail;
use App\Mail\AdminAttendanceSummaryMail; // Import the new Mailable
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

        $remindersSentToTeachers = 0;
        $pendingTeachersForAdminSummary = collect(); // Use a collection to store teachers with pending attendance

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
            if ($attendanceStatus->status == 0 && (!$attendanceStatus->reminderSent() || $force)) {
                try {
                    // Send individual reminder to teacher
                    Mail::to($teacher->email)->send(new AttendanceReminderMail($teacher, $today, false));

                    // Add teacher to the list for admin summary
                    $pendingTeachersForAdminSummary->push($teacher);

                    // Update reminder sent timestamp for the individual teacher's status
                    $attendanceStatus->update([
                        'reminder_sent_at' => now(),
                    ]);
                    $remindersSentToTeachers++;
                    $this->info("Individual reminder sent to {$teacher->name} ({$teacher->email})");
                } catch (\Exception $e) {
                    Log::error("Failed to send attendance reminder to {$teacher->email}: " . $e->getMessage());
                    $this->error("Failed to send individual reminder to {$teacher->name}");
                }
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

        $this->info("Total individual reminders sent to teachers: {$remindersSentToTeachers}");
        Log::info("Attendance reminders check completed. Sent {$remindersSentToTeachers} individual reminders to teachers and consolidated summary to admins.");

        return 0;
    }
}
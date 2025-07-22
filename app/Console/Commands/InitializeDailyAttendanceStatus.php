<?php

namespace App\Console\Commands;

use App\Models\AttendanceStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InitializeDailyAttendanceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:initialize-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize daily attendance status for all teachers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');

        $this->info("Initializing attendance status for {$today}");

        // Get all teachers
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('role', 'teacher');
        })->get();

        $initialized = 0;

        foreach ($teachers as $teacher) {
            // Create attendance status record if it doesn't exist
            $created = AttendanceStatus::firstOrCreate([
                'teacher_id' => $teacher->id,
                'date' => $today,
            ], [
                'status' => 0, // Default to not taken
            ]);

            if ($created->wasRecentlyCreated) {
                $initialized++;
            }
        }

        $this->info("Initialized attendance status for {$initialized} teachers");
        Log::info("Daily attendance status initialized for {$initialized} teachers on {$today}");

        return 0;
    }
}
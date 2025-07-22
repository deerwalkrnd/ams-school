<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;

    protected $table = 'attendance_status';

    protected $fillable = [
        'teacher_id',
        'date',
        'status',
        'reminder_sent_at',
    ];

    protected $casts = [
        'date' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Get the teacher associated with the attendance status.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Scope to get pending attendance (status = 0)
     */
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope to get completed attendance (status = 1)
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Check if reminder has been sent
     */
    public function reminderSent()
    {
        return !is_null($this->reminder_sent_at);
    }
}

<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $teacher;
    protected $date;
    protected $isForAdmin;

    /**
     * Create a new message instance.
     */
    public function __construct(User $teacher, string $date, bool $isForAdmin = false)
    {
        $this->teacher = $teacher;
        $this->date = $date;
        $this->isForAdmin = $isForAdmin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isForAdmin 
            ? 'Attendance Not Taken - Admin Alert | AMS-School'
            : 'Attendance Reminder | AMS-School';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->isForAdmin 
            ? 'layouts.emails.attendanceAlertAdmin'
            : 'layouts.emails.attendanceReminder';

        return new Content(
            markdown: $view,
            with: [
                'teacherName' => $this->teacher->name,
                'teacherEmail' => $this->teacher->email,
                'date' => $this->date,
                'sectionName' => $this->teacher->section->name ?? 'N/A',
                'gradeName' => $this->teacher->section->grade->name ?? 'N/A',
            ]
        );
    }
}

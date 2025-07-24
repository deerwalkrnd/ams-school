<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AdminAttendanceSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $teachers;
    public string $date;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $teachers, string $date)
    {
        $this->teachers = $teachers;
        $this->date = $date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Attendance Summary - ' . Carbon::parse($this->date)->format('M d, Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layouts.emails.admin-attendance-summary',
            with: [
                'teachers' => $this->teachers,
                'date' => $this->date,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
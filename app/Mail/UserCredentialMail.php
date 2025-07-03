<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;

    protected $name;

    protected $roles;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, array $roles)
    {
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = $roles;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Credential Mail | AMS-School',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'layouts.emails.userCredential',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'roles' => $this->roles,
            ]
        );
    }
}

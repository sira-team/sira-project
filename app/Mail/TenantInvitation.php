<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Team $team,
        public string $signedUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to manage {$this->team->name} on Sira App",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant-invitation',
        );
    }
}

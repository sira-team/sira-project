<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Team $team,
        public readonly string $setupUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Einladung zu {$this->team->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-invitation',
        );
    }
}

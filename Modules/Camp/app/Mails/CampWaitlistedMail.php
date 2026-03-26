<?php

declare(strict_types=1);

namespace Modules\Camp\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Camp\Models\CampVisitor;

final class CampWaitlistedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public CampVisitor $registration,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Warteliste - '.$this->registration->camp->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'camp::mails.waitlisted',
        );
    }
}

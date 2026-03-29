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

/**
 * Generic mailable that renders a pre-resolved subject and HTML body.
 * Used by the camp:expire-unpaid-registrations scheduler.
 * For custom Listeners, resolve the template and use Mail::to()->queue(new CampTemplateMail(...)).
 */
final class CampTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $resolvedSubject,
        public readonly string $resolvedBody,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $this->resolvedSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'camp::mails.template',
            with: ['body' => $this->resolvedBody],
        );
    }
}

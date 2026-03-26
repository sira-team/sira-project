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

final class CampPaymentReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public CampVisitor $visitor,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Zahlungserinnerung - '.$this->visitor->camp->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'camp::mails.payment-reminder',
        );
    }
}

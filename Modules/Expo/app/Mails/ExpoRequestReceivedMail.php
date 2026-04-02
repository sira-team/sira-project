<?php

declare(strict_types=1);

namespace Modules\Expo\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Expo\Models\ExpoRequest;

final class ExpoRequestReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ExpoRequest $expoRequest) {}

    public function envelope(): Envelope
    {
        $tenant = $this->expoRequest->tenant;

        return new Envelope(
            to: [$this->expoRequest->email],
            replyTo: [new Address($tenant->email, $tenant->name)],
            subject: 'Expo-Anfrage erhalten — '.$tenant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'expo::mails.request-received',
        );
    }
}

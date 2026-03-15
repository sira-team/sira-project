<?php

declare(strict_types=1);

namespace Modules\Expo\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Expo\Mails\Concerns\HasTenantContext;
use Modules\Expo\Models\ExpoRequest;

final class ExpoRequestAcceptedMail extends Mailable
{
    use HasTenantContext, Queueable, SerializesModels;

    public function __construct(public readonly ExpoRequest $expoRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->expoRequest->email],
            subject: $this->subjectWithTenant('Ihre Expo-Anfrage wurde akzeptiert'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'expo::mails.request-accepted',
        );
    }
}

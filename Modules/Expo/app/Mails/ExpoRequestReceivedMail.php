<?php

declare(strict_types=1);

namespace Modules\Expo\Mails;

use App\Enums\NotificationType;
use App\Models\EmailTemplate;
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
        ['subject' => $subject] = $this->resolve();

        return new Envelope(
            to: [$this->expoRequest->email],
            replyTo: [new Address($tenant->email, $tenant->name)],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        ['body' => $body] = $this->resolve();

        return new Content(
            markdown: 'expo::mails.request-received',
            with: ['body' => $body],
        );
    }

    /**
     * @return array{subject: string, body: string}
     */
    private function resolve(): array
    {
        $tenant = $this->expoRequest->tenant;

        $template = EmailTemplate::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('key', NotificationType::ExpoRequestReceived->value)
            ->firstOrFail();

        return $template->resolve([
            'contact_name' => $this->expoRequest->contact_name,
            'organisation_name' => $this->expoRequest->organisation_name,
            'tenant_name' => $tenant->name,
        ]);
    }
}

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
use Modules\Camp\Models\CampEmailTemplate;
use Modules\Camp\Models\CampVisitor;

/**
 * Generic mailable that renders a pre-resolved subject and HTML body.
 * Used by the camp:expire-unpaid-registrations scheduler.
 * For custom Listeners, use Mail::to(...)->queue(new CampTemplateMail($template, $campVisitor)).
 */
final class CampTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly CampEmailTemplate $template,
        public readonly CampVisitor $visitor,
    ) {}

    public function envelope(): Envelope
    {
        ['subject' => $subject] = $this->resolve();

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        ['body' => $body] = $this->resolve();

        return new Content(
            markdown: 'camp::mails.template',
            with: ['body' => $body],
        );
    }

    /**
     * @return array{subject: string, body: string}
     */
    private function resolve(): array
    {
        $camp = $this->visitor->camp;
        $tenant = $camp->tenant;

        return $this->template->resolve([
            'visitor_name' => $this->visitor->visitor->name,
            'camp_name' => $camp->name,
            'tenant_name' => $tenant->name,
            'price' => number_format((float) $this->visitor->price, 2),
            'iban' => $tenant->iban ?? '',
            'bank_recipient' => $tenant->bank_recipient ?? '',
            'bank_name' => $tenant->bank_name ?? '',
            'bic' => $tenant->bic ?? '',
            'payment_due_date' => $this->visitor->registered_at->addDays(7)->format('d.m.Y'),
            'waitlist_position' => (string) ($this->visitor->waitlist_position ?? ''),
        ]);
    }
}

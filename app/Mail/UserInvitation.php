<?php

declare(strict_types=1);

namespace App\Mail;

use App\Enums\NotificationType;
use App\Models\EmailTemplate;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class UserInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Tenant $tenant,
        public readonly string $setupUrl,
    ) {}

    public function envelope(): Envelope
    {
        ['subject' => $subject] = $this->resolve();

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        ['body' => $body] = $this->resolve();

        return new Content(
            markdown: 'emails.user-invitation',
            with: ['body' => $body],
        );
    }

    /**
     * @return array{subject: string, body: string}
     */
    private function resolve(): array
    {
        $template = EmailTemplate::withoutGlobalScopes()
            ->where('tenant_id', $this->tenant->id)
            ->where('key', NotificationType::UserInvited->value)
            ->firstOrFail();

        return $template->resolve([
            'user_name' => $this->user->name,
            'tenant_name' => $this->tenant->name,
            'setup_url' => $this->setupUrl,
        ]);
    }
}

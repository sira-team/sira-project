<?php

declare(strict_types=1);

namespace App\Jobs\Setup;

use App\Enums\FeatureFlag;
use App\Enums\NotificationType;
use App\Models\EmailTemplate;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SeedEmailTemplates
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Tenant $tenant) {}

    /**
     * @return array<string, array{scope: string, subject: string, body: string}>
     */
    public static function defaults(): array
    {
        return [
            NotificationType::CampReceived->value => [
                'scope' => FeatureFlag::CampPanel->value,
                'subject' => 'Registration Received – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been received. Please transfer the participation fee of <strong>{{ price }} EUR</strong> to the following account to secure your spot:</p><p><strong>Recipient:</strong> {{ bank_recipient }}<br><strong>Bank:</strong> {{ bank_name }}<br><strong>IBAN:</strong> {{ iban }}<br><strong>BIC:</strong> {{ bic }}</p><p>Your registration will be confirmed once payment is received.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::CampConfirmed->value => [
                'scope' => FeatureFlag::CampPanel->value,
                'subject' => 'Registration Confirmed – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been confirmed.</p><p>Please note: payment must be received by <strong>{{ payment_due_date }}</strong>, otherwise your spot will be released.</p><p>We look forward to seeing you!</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::CampWaitlisted->value => [
                'scope' => FeatureFlag::CampPanel->value,
                'subject' => 'Waitlisted – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>unfortunately <strong>{{ camp_name }}</strong> is currently fully booked. You are in position <strong>{{ waitlist_position }}</strong> on the waitlist.</p><p>Please do <strong>not</strong> transfer any payment yet — we will contact you as soon as a spot becomes available.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::CampWaitlistPromoted->value => [
                'scope' => FeatureFlag::CampPanel->value,
                'subject' => 'Spot Available – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>a spot at <strong>{{ camp_name }}</strong> has become available for you! Please transfer the participation fee of <strong>{{ price }} EUR</strong> promptly to secure your place:</p><p><strong>Recipient:</strong> {{ bank_recipient }}<br><strong>Bank:</strong> {{ bank_name }}<br><strong>IBAN:</strong> {{ iban }}<br><strong>BIC:</strong> {{ bic }}</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::CampCancelled->value => [
                'scope' => FeatureFlag::CampPanel->value,
                'subject' => 'Registration Cancelled – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been cancelled.</p><p>If you have any questions, please do not hesitate to contact us.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::ExpoRequestReceived->value => [
                'scope' => FeatureFlag::ExpoPanel->value,
                'subject' => 'Expo Request Received – {{ organisation_name }}',
                'body' => '<p>Dear {{ contact_name }},</p><p>thank you for your expo request. We have received your information and will be in touch shortly.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            NotificationType::UserInvited->value => [
                'scope' => FeatureFlag::TenantApp->value,
                'subject' => 'Invitation to {{ tenant_name }}',
                'body' => '<p>Hello {{ user_name }},</p><p>you have been invited to join the portal of <strong>{{ tenant_name }}</strong>.</p><p>Please click the button below to set your password and activate your account. The link is valid for 7 days.</p><p><a href="{{ setup_url }}">Set Password</a></p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
        ];
    }

    public function handle(): void
    {
        foreach (self::defaults() as $key => $content) {
            EmailTemplate::withoutGlobalScopes()->firstOrCreate(
                ['tenant_id' => $this->tenant->id, 'key' => $key],
                ['subject' => $content['subject'], 'body' => $content['body'], 'scope' => $content['scope']]
            );
        }
    }
}

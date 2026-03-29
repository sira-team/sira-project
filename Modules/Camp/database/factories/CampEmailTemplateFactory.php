<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Models\CampEmailTemplate;

/**
 * @extends Factory<CampEmailTemplate>
 */
final class CampEmailTemplateFactory extends Factory
{
    protected $model = CampEmailTemplate::class;

    /**
     * Default German content for each notification type.
     * Single source of truth used by TenantObserver and camp:seed-email-templates.
     *
     * @return array<string, array{subject: string, body: string}>
     */
    public static function defaults(): array
    {
        return [
            CampNotificationType::Received->value => [
                'subject' => 'Registration Received – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been received. Please transfer the participation fee of <strong>{{ price }} EUR</strong> to the following account to secure your spot:</p><p><strong>Recipient:</strong> {{ bank_recipient }}<br><strong>Bank:</strong> {{ bank_name }}<br><strong>IBAN:</strong> {{ iban }}<br><strong>BIC:</strong> {{ bic }}</p><p>Your registration will be confirmed once payment is received.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            CampNotificationType::Confirmed->value => [
                'subject' => 'Registration Confirmed – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been confirmed.</p><p>Please note: payment must be received by <strong>{{ payment_due_date }}</strong>, otherwise your spot will be released.</p><p>We look forward to seeing you!</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            CampNotificationType::Waitlisted->value => [
                'subject' => 'Waitlisted – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>unfortunately <strong>{{ camp_name }}</strong> is currently fully booked. You are in position <strong>{{ waitlist_position }}</strong> on the waitlist.</p><p>Please do <strong>not</strong> transfer any payment yet — we will contact you as soon as a spot becomes available.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            CampNotificationType::WaitlistPromoted->value => [
                'subject' => 'Spot Available – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>a spot at <strong>{{ camp_name }}</strong> has become available for you! Please transfer the participation fee of <strong>{{ price }} EUR</strong> promptly to secure your place:</p><p><strong>Recipient:</strong> {{ bank_recipient }}<br><strong>Bank:</strong> {{ bank_name }}<br><strong>IBAN:</strong> {{ iban }}<br><strong>BIC:</strong> {{ bic }}</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            CampNotificationType::PaymentReminder->value => [
                'subject' => 'Payment Reminder – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>this is a reminder to transfer the outstanding amount of <strong>{{ price }} EUR</strong> for <strong>{{ camp_name }}</strong>:</p><p><strong>Recipient:</strong> {{ bank_recipient }}<br><strong>Bank:</strong> {{ bank_name }}<br><strong>IBAN:</strong> {{ iban }}<br><strong>BIC:</strong> {{ bic }}</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
            CampNotificationType::Cancelled->value => [
                'subject' => 'Registration Cancelled – {{ camp_name }}',
                'body' => '<p>Dear {{ visitor_name }},</p><p>your registration for <strong>{{ camp_name }}</strong> has been cancelled.</p><p>If you have any questions, please do not hesitate to contact us.</p><p>Kind regards,<br>{{ tenant_name }}</p>',
            ],
        ];
    }

    public function definition(): array
    {
        $type = fake()->randomElement(CampNotificationType::cases());
        $defaults = self::defaults()[$type->value];

        return [
            'tenant_id' => Tenant::factory(),
            'key' => $type,
            'subject' => $defaults['subject'],
            'body' => $defaults['body'],
        ];
    }
}

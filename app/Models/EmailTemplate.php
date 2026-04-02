<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeatureFlag;
use App\Enums\NotificationType;
use App\Traits\BelongsToTenant;
use Database\Factories\EmailTemplateFactory;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $tenant_id
 * @property FeatureFlag $scope
 * @property NotificationType $key
 * @property string $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static EmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 *
 * @property-read Tenant $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class EmailTemplate extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'email_templates';

    /** @var list<string> */
    protected $fillable = [
        'tenant_id',
        'scope',
        'key',
        'subject',
        'body',
    ];

    /**
     * Default content per notification type, used when seeding a new tenant.
     *
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
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{subject: string, body: string}
     */
    public function resolve(array $data): array
    {
        $body = RichContentRenderer::make($this->body)
            ->mergeTags($data)
            ->toHtml();

        $search = array_map(fn (string $key): string => '{{ '.$key.' }}', array_keys($data));

        return [
            'subject' => Str::replace($search, array_values($data), $this->subject),
            'body' => Str::replace($search, array_values($data), $body),
        ];
    }

    protected static function newFactory(): EmailTemplateFactory
    {
        return EmailTemplateFactory::new();
    }

    protected function casts(): array
    {
        return [
            'scope' => FeatureFlag::class,
            'key' => NotificationType::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FeatureFlag;
use App\Enums\NotificationType;
use App\Jobs\Setup\SeedEmailTemplates;
use App\Models\EmailTemplate;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmailTemplate>
 */
final class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    public function definition(): array
    {
        $type = fake()->randomElement(
            array_filter(NotificationType::cases(), fn (NotificationType $t): bool => $t->isCampNotification())
        );
        $defaults = SeedEmailTemplates::defaults()[$type->value];

        return [
            'tenant_id' => Tenant::factory(),
            'scope' => FeatureFlag::CampPanel,
            'key' => $type,
            'subject' => $defaults['subject'],
            'body' => $defaults['body'],
        ];
    }
}

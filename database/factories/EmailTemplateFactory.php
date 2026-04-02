<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FeatureFlag;
use App\Enums\NotificationType;
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
        $campCases = array_filter(
            NotificationType::cases(),
            fn (NotificationType $type): bool => str_starts_with($type->value, 'camp-')
        );

        $type = fake()->randomElement($campCases);
        $defaults = EmailTemplate::defaults()[$type->value];

        return [
            'tenant_id' => Tenant::factory(),
            'scope' => FeatureFlag::CampPanel,
            'key' => $type,
            'subject' => $defaults['subject'],
            'body' => $defaults['body'],
        ];
    }
}

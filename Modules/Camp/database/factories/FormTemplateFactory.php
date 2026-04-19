<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Models\FormTemplate;

/**
 * @extends Factory<FormTemplate>
 */
final class FormTemplateFactory extends Factory
{
    protected $model = FormTemplate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->words(3, true).' Form',
        ];
    }
}

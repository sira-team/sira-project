<?php

declare(strict_types=1);

namespace Modules\Camp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Camp\Enums\FormFieldType;
use Modules\Camp\Models\FormTemplate;
use Modules\Camp\Models\FormTemplateField;

/**
 * @extends Factory<FormTemplateField>
 */
final class FormTemplateFieldFactory extends Factory
{
    protected $model = FormTemplateField::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(FormFieldType::cases());

        return [
            'form_template_id' => FormTemplate::factory(),
            'label' => fake()->words(2, true),
            'type' => $type,
            'required' => fake()->boolean(),
            'order' => fake()->numberBetween(0, 100),
            'help_text' => fake()->optional()->sentence(),
            'options' => $type->hasOptions()
                ? fake()->words(4)
                : null,
        ];
    }
}

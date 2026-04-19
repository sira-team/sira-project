<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\FormTemplateFieldFactory;
use Modules\Camp\Enums\FormFieldType;

/**
 * @property int $id
 * @property int $form_template_id
 * @property string $label
 * @property FormFieldType $type
 * @property bool $required
 * @property int $order
 * @property string|null $help_text
 * @property array<int, string>|null $options
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FormTemplate|null $formTemplate
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplateField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplateField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplateField query()
 * @method static FormTemplateFieldFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class FormTemplateField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_template_id',
        'label',
        'type',
        'required',
        'order',
        'help_text',
        'options',
    ];

    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }

    protected static function newFactory(): FormTemplateFieldFactory
    {
        return FormTemplateFieldFactory::new();
    }

    protected function casts(): array
    {
        return [
            'type' => FormFieldType::class,
            'required' => 'boolean',
            'order' => 'integer',
            'options' => 'array',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $camp_visitor_id
 * @property int|null $form_template_field_id
 * @property string|null $field_label
 * @property string|null $field_type
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CampVisitor|null $campVisitor
 * @property-read FormTemplateField|null $field
 *
 * @mixin \Eloquent
 */
final class CampRegistrationAnswer extends Model
{
    protected $fillable = [
        'camp_visitor_id',
        'form_template_field_id',
        'field_label',
        'field_type',
        'value',
    ];

    public function campVisitor(): BelongsTo
    {
        return $this->belongsTo(CampVisitor::class, 'camp_visitor_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormTemplateField::class, 'form_template_field_id');
    }

    /**
     * Returns the field label, using the snapshot if the live field no longer exists.
     */
    public function getDisplayLabel(): string
    {
        return $this->field_label ?? $this->field->label ?? __('Deleted field');
    }

    /**
     * Returns the decoded value for display.
     * Array types (checkbox) are stored as JSON; all others as plain text.
     */
    public function getDecodedValue(): mixed
    {
        if ($this->value === null) {
            return null;
        }

        $decoded = json_decode($this->value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $this->value;
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Tenant;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\FormTemplateFactory;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant|null $tenant
 * @property-read Collection<int, FormTemplateField> $fields
 * @property-read int|null $fields_count
 * @property-read Collection<int, Camp> $camps
 * @property-read int|null $camps_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormTemplate withoutTrashed()
 * @method static FormTemplateFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class FormTemplate extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(FormTemplateField::class)->orderBy('order');
    }

    public function camps(): HasMany
    {
        return $this->hasMany(Camp::class);
    }

    protected static function newFactory(): FormTemplateFactory
    {
        return FormTemplateFactory::new();
    }
}

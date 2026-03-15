<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Models\Tenant;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string|null $description
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, StationDigitalMaterial> $digitalMaterials
 * @property-read int|null $digital_materials_count
 * @property-read Collection<int, Expo> $expos
 * @property-read int|null $expos_count
 * @property-read Collection<int, StationPhysicalMaterial> $physicalMaterials
 * @property-read int|null $physical_materials_count
 * @property-read Tenant $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Station withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class Station extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'sort_order',
    ];

    public function physicalMaterials(): HasMany
    {
        return $this->hasMany(StationPhysicalMaterial::class)->orderBy('sort_order');
    }

    public function digitalMaterials(): HasMany
    {
        return $this->hasMany(StationDigitalMaterial::class)->orderBy('sort_order');
    }

    public function expos(): BelongsToMany
    {
        return $this->belongsToMany(Expo::class, 'expo_stations')
            ->withPivot('responsible_user_id', 'sort_order');
    }
}

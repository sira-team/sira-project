<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Expo\Enums\PhysicalMaterialType;

/**
 * @property int $id
 * @property int $station_id
 * @property PhysicalMaterialType $type
 * @property string $name
 * @property string|null $notes
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Station $station
 * @property string|null $image
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationPhysicalMaterial whereImage($value)
 *
 * @mixin \Eloquent
 */
final class StationPhysicalMaterial extends Model
{
    protected $fillable = [
        'station_id',
        'type',
        'name',
        'notes',
        'sort_order',
        'image',
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    protected function casts(): array
    {
        return [
            'type' => PhysicalMaterialType::class,
        ];
    }
}

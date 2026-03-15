<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Expo\Enums\PhysicalMaterialType;

class StationPhysicalMaterial extends Model
{
    protected $fillable = [
        'station_id',
        'type',
        'name',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => PhysicalMaterialType::class,
        ];
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}

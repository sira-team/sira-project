<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Expo\Enums\DigitalMaterialType;

class StationDigitalMaterial extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'station_id',
        'tenant_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
        'language',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'file_type' => DigitalMaterialType::class,
        ];
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected static function booted(): void
    {
        static::creating(function (self $material) {
            // Set uploaded_by to current user if not set
            if (! $material->uploaded_by) {
                $material->uploaded_by = auth()->id();
            }
        });
    }
}

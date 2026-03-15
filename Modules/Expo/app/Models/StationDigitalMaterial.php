<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Models\Tenant;
use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Expo\Enums\DigitalMaterialType;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $station_id
 * @property string $title
 * @property string $file_path
 * @property DigitalMaterialType $file_type
 * @property int $file_size
 * @property int $uploaded_by
 * @property string $language
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Station $station
 * @property-read Tenant $tenant
 * @property-read User $uploadedByUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereUploadedBy($value)
 *
 * @property int $file_size_kb
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationDigitalMaterial whereFileSizeKb($value)
 *
 * @mixin \Eloquent
 */
final class StationDigitalMaterial extends Model
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
        self::creating(function (self $material) {
            // Set uploaded_by to current user if not set
            if (! $material->uploaded_by) {
                $material->uploaded_by = auth()->id();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'file_type' => DigitalMaterialType::class,
        ];
    }
}

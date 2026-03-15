<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Models\Tenant;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Expo\Enums\ExpoStatus;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int|null $expo_request_id
 * @property string $name
 * @property string $location_name
 * @property string|null $location_address
 * @property Carbon $date
 * @property ExpoStatus $status
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ExpoRequest|null $expoRequest
 * @property-read Collection<int, Station> $stations
 * @property-read int|null $stations_count
 * @property-read Tenant $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereExpoRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereLocationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expo withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class Expo extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'expo_request_id',
        'name',
        'location_name',
        'location_address',
        'date',
        'status',
        'notes',
    ];

    public function expoRequest(): BelongsTo
    {
        return $this->belongsTo(ExpoRequest::class);
    }

    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class, 'expo_stations')
            ->withPivot('responsible_user_id', 'sort_order')
            ->orderBy('sort_order');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => ExpoStatus::class,
        ];
    }
}

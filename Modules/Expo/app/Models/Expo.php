<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Expo\Enums\ExpoStatus;

class Expo extends Model
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

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => ExpoStatus::class,
        ];
    }

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
}

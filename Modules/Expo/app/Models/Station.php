<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
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

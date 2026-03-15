<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (! $model->tenant_id && app()->bound(Tenant::class)) {
                $model->tenant_id = app(Tenant::class)->id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->bound(Tenant::class)) {
                $builder->where(
                    $builder->getModel()->getTable().'.tenant_id',
                    app(Tenant::class)->id
                );
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (! $model->team_id && app()->bound(Team::class)) {
                $model->team_id = app(Team::class)->id;
            }
        });

        static::addGlobalScope('team', function (Builder $builder) {
            if (app()->bound(Team::class)) {
                $builder->where(
                    $builder->getModel()->getTable().'.team_id',
                    app(Team::class)->id
                );
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

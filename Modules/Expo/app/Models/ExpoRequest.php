<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Expo\Enums\ExpoRequestStatus;

class ExpoRequest extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'status',
        'contact_name',
        'organisation_name',
        'email',
        'phone',
        'city',
        'preferred_date_from',
        'preferred_date_to',
        'expected_visitors',
        'message',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ExpoRequestStatus::class,
            'preferred_date_from' => 'date',
            'preferred_date_to' => 'date',
        ];
    }

    public function expo(): HasOne
    {
        return $this->hasOne(Expo::class);
    }
}

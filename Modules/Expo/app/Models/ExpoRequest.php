<?php

declare(strict_types=1);

namespace Modules\Expo\Models;

use App\Models\Tenant;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Expo\Enums\ExpoRequestStatus;

/**
 * @property int $id
 * @property int $tenant_id
 * @property ExpoRequestStatus $status
 * @property string $contact_name
 * @property string $organisation_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $city
 * @property Carbon|null $preferred_date_from
 * @property Carbon|null $preferred_date_to
 * @property string|null $message
 * @property string|null $internal_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Expo|null $expo
 * @property-read Tenant $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereExpectedVisitors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereOrganisationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest wherePreferredDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest wherePreferredDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpoRequest withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class ExpoRequest extends Model
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
        'message',
        'internal_notes',
    ];

    public function expo(): HasOne
    {
        return $this->hasOne(Expo::class);
    }

    protected function casts(): array
    {
        return [
            'status' => ExpoRequestStatus::class,
            'preferred_date_from' => 'date',
            'preferred_date_to' => 'date',
        ];
    }
}

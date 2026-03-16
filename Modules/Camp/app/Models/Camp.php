<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Tenant;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Enums\CampTargetGroup;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property int $capacity
 * @property float $price
 * @property string $target_group
 * @property int|null $age_min
 * @property int|null $age_max
 * @property string $gender_policy
 * @property bool $food_provided
 * @property bool $participants_bring_food
 * @property int|null $predicted_participants
 * @property int|null $predicted_supporters
 * @property bool $registration_open
 * @property Carbon|null $registration_opens_at
 * @property Carbon|null $registration_deadline
 * @property string $iban
 * @property string $bank_recipient
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant|null $tenant
 * @property-read HostelContract|null $hostelContract
 * @property-read int|null $hostelContract_count
 * @property-read Collection<int, CampRegistration> $registrations
 * @property-read int|null $registrations_count
 * @property-read Collection<int, CampExpense> $expenses
 * @property-read int|null $expenses_count
 * @property-read int $nights
 * @property-read int $confirmedRegistrationsCount
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereAgeMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereAgeMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereBankRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereFoodProvided($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereGenderPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereParticipantsBringFood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp wherePredictedParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp wherePredictedSupporters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereRegistrationDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereRegistrationOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereRegistrationOpensAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereTargetGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class Camp extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'starts_at',
        'ends_at',
        'capacity',
        'price',
        'target_group',
        'age_min',
        'age_max',
        'gender_policy',
        'food_provided',
        'participants_bring_food',
        'predicted_participants',
        'predicted_supporters',
        'registration_open',
        'registration_opens_at',
        'registration_deadline',
        'iban',
        'bank_recipient',
        'notes',
    ];

    public function hostelContract(): HasOne
    {
        return $this->hasOne(HostelContract::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CampRegistration::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(CampExpense::class);
    }

    public function getNightsAttribute(): int
    {
        return (int) $this->starts_at->diffInDays($this->ends_at);
    }

    public function getConfirmedRegistrationsCountAttribute(): int
    {
        return $this->registrations()
            ->where('status', CampRegistrationStatus::Confirmed)
            ->count();
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'price' => 'decimal:2',
            'target_group' => CampTargetGroup::class,
            'gender_policy' => CampGenderPolicy::class,
            'food_provided' => 'boolean',
            'participants_bring_food' => 'boolean',
            'registration_open' => 'boolean',
            'registration_opens_at' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }
}

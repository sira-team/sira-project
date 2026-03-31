<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visitor;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\CampFactory;
use Modules\Camp\Enums\CampGenderPolicy;
use Modules\Camp\Enums\CampTargetGroup;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $description
 * @property string|null $internal_notes
 * @property CampTargetGroup $target_group
 * @property int|null $age_min
 * @property int|null $age_max
 * @property CampGenderPolicy $gender_policy
 * @property-read bool $registration_is_open
 * @property Carbon|null $registration_opens_at
 * @property Carbon|null $registration_ends_at
 * @property float $price_per_participant
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant|null $tenant
 * @property-read CampContract|null $contract
 * @property-read Collection<int, CampVisitor> $campVisitors
 * @property-read int|null $camp_visitors_count
 * @property-read Collection<int, Visitor> $visitors
 * @property-read int|null $visitors_count
 * @property-read Collection<int, CampExpense> $expenses
 * @property-read int|null $expenses_count
 * @property-read Collection<int, User> $supportStaff
 * @property-read int|null $support_staff_count
 * @property-read int $nights
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property-read CampVisitor|null $pivot
 * @property int|null $max_visitors_male
 * @property int|null $max_visitors_female
 * @property int|null $max_visitors_all
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp withoutTrashed()
 * @method static \Modules\Camp\Database\Factories\CampFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereAgeMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereAgeMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereGenderPolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp wherePricePerParticipant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereRegistrationEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereRegistrationOpensAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereTargetGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp whereUpdatedAt($value)
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
        'description',
        'internal_notes',
        'target_group',
        'age_min',
        'age_max',
        'gender_policy',
        'registration_opens_at',
        'registration_ends_at',
        'price_per_participant',
        'max_visitors_male',
        'max_visitors_female',
        'max_visitors_all',
    ];

    public function contract(): HasOne
    {
        return $this->hasOne(CampContract::class);
    }

    public function visitors(): BelongsToMany
    {
        return $this->belongsToMany(Visitor::class, 'camp_visitor')
            ->withPivot('id', 'status', 'wishes', 'room_id', 'waitlist_position', 'registered_at')
            ->using(CampVisitor::class)
            ->withTimestamps();
    }

    public function campVisitors(): HasMany
    {
        return $this->hasMany(CampVisitor::class);
    }

    /**
     * @return BelongsToMany<User, $this, CampUser>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'camp_user')
            ->using(CampUser::class)
            ->withPivot('id', 'room_id');
    }

    public function campUsers(): HasMany
    {
        return $this->hasMany(CampUser::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(CampExpense::class);
    }

    public function getNightsAttribute(): int
    {
        return (int) $this->starts_at->diffInDays($this->ends_at);
    }

    public function registrationIsOpen(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->registration_opens_at?->isPast() && $this->registration_ends_at?->isFuture(),
        );
    }

    protected static function newFactory(): CampFactory
    {
        return CampFactory::new();
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'price_per_participant' => 'decimal:2',
            'target_group' => CampTargetGroup::class,
            'gender_policy' => CampGenderPolicy::class,
            'registration_opens_at' => 'datetime',
            'registration_ends_at' => 'datetime',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Visitor;
use App\Traits\BelongsToTenant;
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
use Modules\Camp\Enums\CampRegistrationStatus;
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
 * @property bool $food_provided
 * @property bool $participants_bring_food
 * @property bool $registration_open
 * @property Carbon|null $registration_opens_at
 * @property Carbon|null $registration_deadline
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
 * @property-read int $confirmedVisitorsCount
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Camp query()
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
        'description',
        'internal_notes',
        'target_group',
        'age_min',
        'age_max',
        'gender_policy',
        'food_provided',
        'participants_bring_food',
        'registration_open',
        'registration_opens_at',
        'registration_deadline',
        'price_per_participant',
    ];

    public function contract(): HasOne
    {
        return $this->hasOne(CampContract::class);
    }

    public function campVisitors(): HasMany
    {
        return $this->hasMany(CampVisitor::class);
    }

    public function visitors(): BelongsToMany
    {
        return $this->belongsToMany(Visitor::class, 'camp_visitor')
            ->withPivot('id', 'status', 'price', 'special_wishes', 'room_id', 'waitlist_position', 'registered_at')
            ->withTimestamps();
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(CampExpense::class);
    }

    public function supportStaff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'camp_user');
    }

    public function getNightsAttribute(): int
    {
        return (int) $this->starts_at->diffInDays($this->ends_at);
    }

    public function getConfirmedVisitorsCountAttribute(): int
    {
        return $this->visitors()
            ->wherePivot('status', CampRegistrationStatus::Confirmed)
            ->count();
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
            'food_provided' => 'boolean',
            'participants_bring_food' => 'boolean',
            'registration_open' => 'boolean',
            'registration_opens_at' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }
}

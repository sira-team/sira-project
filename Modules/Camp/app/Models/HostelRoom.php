<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\HostelRoomFactory;

/**
 * @property int $id
 * @property int $hostel_id
 * @property string $name
 * @property int $capacity
 * @property string $floor
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Hostel|null $hostel
 * @property-read Collection<int, CampVisitor> $campVisitors
 * @property-read int|null $camp_visitors_count
 * @property-read Collection<int, CampUser> $campUsers
 * @property-read int|null $camp_users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereHostelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelRoom whereUpdatedAt($value)
 * @method static \Modules\Camp\Database\Factories\HostelRoomFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class HostelRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'name',
        'capacity',
        'floor',
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function campVisitors(): HasMany
    {
        return $this->hasMany(CampVisitor::class, 'room_id');
    }

    public function campUsers(): HasMany
    {
        return $this->hasMany(CampUser::class, 'room_id');
    }

    public function scopeAvailableForStaff(Builder $query, Camp $camp): void
    {
        $query
            ->where('hostel_id', $camp->contract->hostel_id)
            ->whereDoesntHave('campVisitors', fn (Builder $q) => $q->where('camp_id', $camp->id));
    }

    public function scopeAvailableForVisitors(Builder $query, Camp $camp): void
    {
        $query
            ->where('hostel_id', $camp->contract->hostel_id)
            ->whereDoesntHave('campUsers', fn (Builder $q) => $q->where('camp_id', $camp->id));
    }

    protected static function newFactory(): HostelRoomFactory
    {
        return HostelRoomFactory::new();
    }
}

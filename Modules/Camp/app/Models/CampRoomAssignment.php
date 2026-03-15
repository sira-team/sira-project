<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $camp_registration_id
 * @property int $hostel_room_id
 * @property Carbon $assigned_at
 * @property int $assigned_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CampRegistration|null $registration
 * @property-read HostelRoom|null $room
 * @property-read User|null $assignedByUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereCampRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereHostelRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRoomAssignment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class CampRoomAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_registration_id',
        'hostel_room_id',
        'assigned_at',
        'assigned_by',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CampRegistration::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class, 'hostel_room_id');
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }
}

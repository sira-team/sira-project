<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property int|null $room_id
 * @property-read HostelRoom|null $room
 * @property-read Camp|null $camp
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereId($value)
 *
 * @mixin \Eloquent
 */
final class CampUser extends Pivot
{
    public $timestamps = false;

    protected $table = 'camp_user';

    protected $fillable = [
        'camp_id',
        'user_id',
        'room_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class);
    }

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }
}

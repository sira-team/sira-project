<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $camp_id
 * @property int $user_id
 * @property int|null $room_id
 * @property-read HostelRoom|null $room
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampUser whereUserId($value)
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

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class);
    }
}

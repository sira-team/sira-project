<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\CampVisitorFactory;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Notifications\CampStatusNotification;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $visitor_id
 * @property VisitorStatus $status
 * @property float $price
 * @property string|null $special_wishes
 * @property int|null $room_id
 * @property int|null $waitlist_position
 * @property Carbon $registered_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Camp|null $camp
 * @property-read Visitor|null $visitor
 * @property-read HostelRoom|null $room
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereVisitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereWaitlistPosition($value)
 * @method static \Modules\Camp\Database\Factories\CampVisitorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampVisitor whereSpecialWishes($value)
 *
 * @mixin \Eloquent
 */
final class CampVisitor extends Pivot
{
    use HasFactory;

    protected $table = 'camp_visitor';

    protected $fillable = [
        'camp_id',
        'visitor_id',
        'status',
        'price',
        'special_wishes',
        'room_id',
        'waitlist_position',
        'registered_at',
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }

    public function notify(CampNotificationType $type): void
    {
        $this->visitor->notify(new CampStatusNotification($type, $this));
    }

    protected static function newFactory(): CampVisitorFactory
    {
        return CampVisitorFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => VisitorStatus::class,
            'price' => 'decimal:2',
            'registered_at' => 'datetime',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Participant;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\CampPaymentStatus;
use Modules\Camp\Enums\CampRegistrationStatus;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $visitor_id
 * @property int $participant_id
 * @property string $status
 * @property string $payment_status
 * @property int|null $waitlist_position
 * @property Carbon $registered_at
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property string|null $internal_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Camp|null $camp
 * @property-read Visitor|null $visitor
 * @property-read Participant|null $participant
 * @property-read CampRoomAssignment|null $roomAssignment
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereParticipantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereVisitorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampRegistration whereWaitlistPosition($value)
 *
 * @mixin \Eloquent
 */
final class CampRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'visitor_id',
        'participant_id',
        'status',
        'payment_status',
        'waitlist_position',
        'registered_at',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
        'internal_notes',
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function roomAssignment(): HasOne
    {
        return $this->hasOne(CampRoomAssignment::class);
    }

    protected function casts(): array
    {
        return [
            'status' => CampRegistrationStatus::class,
            'payment_status' => CampPaymentStatus::class,
            'registered_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }
}

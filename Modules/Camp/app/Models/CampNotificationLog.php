<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\CampNotificationType;

/**
 * @property int $id
 * @property int $camp_registration_id
 * @property string $type
 * @property Carbon $sent_at
 * @property string $recipient_email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CampRegistration|null $registration
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereCampRegistrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampNotificationLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class CampNotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_registration_id',
        'type',
        'sent_at',
        'recipient_email',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CampRegistration::class);
    }

    protected function casts(): array
    {
        return [
            'type' => CampNotificationType::class,
            'sent_at' => 'datetime',
        ];
    }
}

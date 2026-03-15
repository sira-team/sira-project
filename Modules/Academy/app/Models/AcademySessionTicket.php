<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\AcademySessionTicketFactory;

/**
 * @property int $id
 * @property int $academy_enrollment_id
 * @property int $academy_session_id
 * @property string $code
 * @property Carbon $issued_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AcademyEnrollment|null $enrollment
 * @property-read AcademySession $session
 *
 * @method static \Modules\Academy\Database\Factories\AcademySessionTicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereAcademyEnrollmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereAcademySessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereIssuedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySessionTicket whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class AcademySessionTicket extends Model
{
    use HasFactory;

    protected $fillable = ['academy_enrollment_id', 'academy_session_id', 'code', 'issued_at'];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(AcademyEnrollment::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AcademySession::class, 'academy_session_id');
    }

    protected static function newFactory(): AcademySessionTicketFactory
    {
        return AcademySessionTicketFactory::new();
    }

    protected function casts(): array
    {
        return ['issued_at' => 'datetime'];
    }
}

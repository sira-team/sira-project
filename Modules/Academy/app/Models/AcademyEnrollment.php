<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use App\Models\Tenant;
use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\AcademyEnrollmentFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $tenant_id
 * @property int $academy_level_id
 * @property Carbon $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AcademyLevel $level
 * @property-read Collection<int, QuizAttempt> $quizAttempts
 * @property-read int|null $quiz_attempts_count
 * @property-read Tenant $tenant
 * @property-read Collection<int, AcademySessionTicket> $tickets
 * @property-read int|null $tickets_count
 * @property-read User $user
 *
 * @method static \Modules\Academy\Database\Factories\AcademyEnrollmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereAcademyLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyEnrollment whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class AcademyEnrollment extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['user_id', 'tenant_id', 'academy_level_id', 'started_at', 'completed_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(AcademyLevel::class, 'academy_level_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(AcademySessionTicket::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    protected static function newFactory(): AcademyEnrollmentFactory
    {
        return AcademyEnrollmentFactory::new();
    }

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'completed_at' => 'datetime'];
    }
}

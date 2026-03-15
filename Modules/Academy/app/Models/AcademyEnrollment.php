<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academy\Database\Factories\AcademyEnrollmentFactory;

class AcademyEnrollment extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['user_id', 'team_id', 'academy_level_id', 'started_at', 'completed_at'];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    protected static function newFactory(): AcademyEnrollmentFactory
    {
        return AcademyEnrollmentFactory::new();
    }

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
}

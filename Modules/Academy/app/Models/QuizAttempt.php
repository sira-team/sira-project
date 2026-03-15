<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academy\Database\Factories\QuizAttemptFactory;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['academy_enrollment_id', 'quiz_id', 'academy_session_ticket_id', 'attempted_at', 'completed_at', 'score_percent', 'passed'];

    protected function casts(): array
    {
        return ['attempted_at' => 'datetime', 'completed_at' => 'datetime', 'score_percent' => 'integer', 'passed' => 'boolean'];
    }

    protected static function newFactory(): QuizAttemptFactory
    {
        return QuizAttemptFactory::new();
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(AcademyEnrollment::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(AcademySessionTicket::class, 'academy_session_ticket_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }
}

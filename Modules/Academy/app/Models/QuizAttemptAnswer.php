<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academy\Database\Factories\QuizAttemptAnswerFactory;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_attempt_id', 'quiz_question_id', 'quiz_option_id'];

    protected static function newFactory(): QuizAttemptAnswerFactory
    {
        return QuizAttemptAnswerFactory::new();
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(QuizOption::class, 'quiz_option_id');
    }
}

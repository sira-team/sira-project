<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\QuizAttemptAnswerFactory;

/**
 * @property int $id
 * @property int $quiz_attempt_id
 * @property int $quiz_question_id
 * @property array<int> $selected_options
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizAttempt $attempt
 * @property-read QuizQuestion $question
 *
 * @method static \Modules\Academy\Database\Factories\QuizAttemptAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereQuizAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereQuizQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereSelectedOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizAttemptAnswer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_attempt_id', 'quiz_question_id', 'selected_options'];

    public function casts(): array
    {
        return [
            'selected_options' => 'array',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    protected static function newFactory(): QuizAttemptAnswerFactory
    {
        return QuizAttemptAnswerFactory::new();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\QuizOptionFactory;

/**
 * @property int $id
 * @property int $quiz_question_id
 * @property string $text
 * @property bool $is_correct
 * @property float $points
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read QuizQuestion $question
 *
 * @method static \Modules\Academy\Database\Factories\QuizOptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereQuizQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuizOption wherePoints($value)
 *
 * @mixin \Eloquent
 */
final class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'text',
        'is_correct',
        'points',
    ];

    public function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'points' => 'float',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    protected static function newFactory(): QuizOptionFactory
    {
        return QuizOptionFactory::new();
    }
}

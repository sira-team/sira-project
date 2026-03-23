<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\QuizFactory;

/**
 * @property int $id
 * @property int $academy_level_id
 * @property string $title
 * @property int $max_attempts
 * @property int $min_days_between_attempts
 * @property int $passing_score_percent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AcademyLevel $level
 * @property-read Collection<int, QuizQuestion> $questions
 * @property-read int|null $questions_count
 *
 * @method static \Modules\Academy\Database\Factories\QuizFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereAcademyLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereMinDaysBetweenAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz wherePassingScorePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quiz whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_level_id',
        'title',
        'max_attempts',
        'min_days_between_attempts',
        'passing_score_percent',
    ];

    public function casts(): array
    {
        return [
            'max_attempts' => 'integer',
            'min_days_between_attempts' => 'integer',
            'passing_score_percent' => 'integer',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(AcademyLevel::class, 'academy_level_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    protected static function newFactory(): QuizFactory
    {
        return QuizFactory::new();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\AcademySessionFactory;

/**
 * @property int $id
 * @property int $academy_level_id
 * @property string $title
 * @property string|null $description
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AcademyLevel $level
 * @property-read Quiz|null $quiz
 * @property-read Collection<int, Quiz> $quizzes
 * @property-read int|null $quizzes_count
 *
 * @method static \Modules\Academy\Database\Factories\AcademySessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereAcademyLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademySession whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class AcademySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_level_id',
        'title',
        'description',
        'sort_order',
    ];

    public function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(AcademyLevel::class, 'academy_level_id');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    /** @return HasMany<Quiz, $this> */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    protected static function newFactory(): AcademySessionFactory
    {
        return AcademySessionFactory::new();
    }
}

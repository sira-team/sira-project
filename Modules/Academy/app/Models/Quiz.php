<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academy\Database\Factories\QuizFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_session_id',
        'title',
        'max_attempts',
        'min_days_between_attempts',
        'passing_score_percent',
    ];

    protected static function newFactory(): QuizFactory
    {
        return QuizFactory::new();
    }

    public function casts(): array
    {
        return [
            'max_attempts' => 'integer',
            'min_days_between_attempts' => 'integer',
            'passing_score_percent' => 'integer',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(AcademySession::class, 'academy_session_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }
}

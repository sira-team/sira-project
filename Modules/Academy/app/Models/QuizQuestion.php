<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academy\Database\Factories\QuizQuestionFactory;
use Modules\Academy\Enums\QuizQuestionType;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'sort_order',
    ];

    protected static function newFactory(): QuizQuestionFactory
    {
        return QuizQuestionFactory::new();
    }

    public function casts(): array
    {
        return [
            'type' => QuizQuestionType::class,
            'sort_order' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizOption::class);
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Academy\Database\Factories\QuizOptionFactory;

class QuizOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'text',
        'is_correct',
    ];

    protected static function newFactory(): QuizOptionFactory
    {
        return QuizOptionFactory::new();
    }

    public function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}

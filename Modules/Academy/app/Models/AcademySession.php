<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Academy\Database\Factories\AcademySessionFactory;

class AcademySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_level_id',
        'title',
        'description',
        'sort_order',
    ];

    protected static function newFactory(): AcademySessionFactory
    {
        return AcademySessionFactory::new();
    }

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
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Academy\Database\Factories\AcademyLevelFactory;

class AcademyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_months',
        'sort_order',
    ];

    protected static function newFactory(): AcademyLevelFactory
    {
        return AcademyLevelFactory::new();
    }

    public function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(AcademySession::class)->orderBy('sort_order');
    }
}

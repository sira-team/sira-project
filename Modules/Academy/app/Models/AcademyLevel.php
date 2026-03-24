<?php

declare(strict_types=1);

namespace Modules\Academy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Academy\Database\Factories\AcademyLevelFactory;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $duration_months
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Modules\Academy\Database\Factories\AcademyLevelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereDurationMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademyLevel whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class AcademyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_months',
        'sort_order',
    ];

    public function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): AcademyLevelFactory
    {
        return AcademyLevelFactory::new();
    }
}

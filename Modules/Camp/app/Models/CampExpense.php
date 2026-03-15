<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\CampExpenseCategory;

/**
 * @property int $id
 * @property int $camp_id
 * @property string $category
 * @property string $title
 * @property string|null $description
 * @property float $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Camp|null $camp
 * @property-read \App\Models\Tenant|null $tenant
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class CampExpense extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'camp_id',
        'category',
        'title',
        'description',
        'amount',
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    protected function casts(): array
    {
        return [
            'category' => CampExpenseCategory::class,
            'amount' => 'decimal:2',
        ];
    }
}

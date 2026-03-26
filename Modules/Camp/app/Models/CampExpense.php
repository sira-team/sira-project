<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Models\Tenant;
use App\Models\User;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\CampExpenseFactory;
use Modules\Camp\Enums\CampExpenseCategory;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property CampExpenseCategory $category
 * @property string $title
 * @property string|null $description
 * @property float $amount
 * @property string|null $receipt_image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Camp|null $camp
 * @property-read User|null $submittedBy
 * @property-read Tenant|null $tenant
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereReceiptImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampExpense whereUserId($value)
 *
 * @mixin \Eloquent
 */
final class CampExpense extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'camp_id',
        'user_id',
        'category',
        'title',
        'description',
        'amount',
        'receipt_image',
    ];

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function newFactory(): CampExpenseFactory
    {
        return CampExpenseFactory::new();
    }

    protected function casts(): array
    {
        return [
            'category' => CampExpenseCategory::class,
            'amount' => 'decimal:2',
        ];
    }
}

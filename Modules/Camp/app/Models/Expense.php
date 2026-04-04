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
use Modules\Camp\Database\Factories\ExpenseFactory;
use Modules\Camp\Enums\ExpenseCategory;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $user_id
 * @property ExpenseCategory $category
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReceiptImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUserId($value)
 * @method static \Modules\Camp\Database\Factories\ExpenseFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
final class Expense extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'camp_expenses';

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

    protected static function booted(): void
    {
        parent::booted();

        self::creating(function (Expense $expense) {
            $expense->user_id = $expense->user_id ?? auth()->id();
        });
    }

    protected static function newFactory(): ExpenseFactory
    {
        return ExpenseFactory::new();
    }

    protected function casts(): array
    {
        return [
            'category' => ExpenseCategory::class,
            'amount' => 'decimal:2',
        ];
    }
}

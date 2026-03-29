<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Camp\Database\Factories\CampEmailTemplateFactory;
use Modules\Camp\Enums\CampNotificationType;

/**
 * @property int $id
 * @property int $tenant_id
 * @property CampNotificationType $key
 * @property string $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Modules\Camp\Database\Factories\CampEmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampEmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampEmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampEmailTemplate query()
 *
 * @mixin \Eloquent
 */
final class CampEmailTemplate extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'key',
        'subject',
        'body',
    ];

    protected static function newFactory(): CampEmailTemplateFactory
    {
        return CampEmailTemplateFactory::new();
    }

    protected function casts(): array
    {
        return [
            'key' => CampNotificationType::class,
        ];
    }
}

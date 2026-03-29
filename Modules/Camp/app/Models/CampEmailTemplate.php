<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use App\Traits\BelongsToTenant;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    /**
     * @param  array<string, string>  $data
     * @return array{subject: string, body: string}
     */
    public function resolve(array $data): array
    {
        $body = RichContentRenderer::make($this->body)
            ->mergeTags($data)
            ->toHtml();

        $search = array_map(fn (string $key): string => '{{ '.$key.' }}', array_keys($data));

        return [
            'subject' => Str::replace($search, array_values($data), $this->subject),
            'body' => Str::replace($search, array_values($data), $body),
        ];
    }

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

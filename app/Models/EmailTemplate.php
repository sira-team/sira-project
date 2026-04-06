<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeatureFlag;
use App\Enums\NotificationType;
use App\Traits\BelongsToTenant;
use Database\Factories\EmailTemplateFactory;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $tenant_id
 * @property FeatureFlag $scope
 * @property NotificationType $key
 * @property string $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Tenant $tenant
 *
 * @method static EmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class EmailTemplate extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'email_templates';

    /** @var list<string> */
    protected $fillable = [
        'tenant_id',
        'scope',
        'key',
        'subject',
        'body',
    ];

    /**
     * @param  array<string, mixed>  $data
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

    protected static function newFactory(): EmailTemplateFactory
    {
        return EmailTemplateFactory::new();
    }

    protected function casts(): array
    {
        return [
            'scope' => FeatureFlag::class,
            'key' => NotificationType::class,
        ];
    }
}

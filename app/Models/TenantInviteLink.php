<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Tenant $tenant
 *
 * @method static Builder<static>|TenantInviteLink newModelQuery()
 * @method static Builder<static>|TenantInviteLink newQuery()
 * @method static Builder<static>|TenantInviteLink query()
 * @method static Builder<static>|TenantInviteLink valid()
 * @method static Builder<static>|TenantInviteLink whereCreatedAt($value)
 * @method static Builder<static>|TenantInviteLink whereExpiresAt($value)
 * @method static Builder<static>|TenantInviteLink whereId($value)
 * @method static Builder<static>|TenantInviteLink whereTenantId($value)
 * @method static Builder<static>|TenantInviteLink whereToken($value)
 * @method static Builder<static>|TenantInviteLink whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class TenantInviteLink extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'tenant_id',
        'token',
        'expires_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }
}

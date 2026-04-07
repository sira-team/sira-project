<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\AsTenantSettings;
use App\Observers\TenantObserver;
use App\ValueObjects\TenantSettings;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $city
 * @property string $country
 * @property string $email
 * @property string|null $iban
 * @property string|null $bank_recipient_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property string|null $bank_recipient
 * @property string|null $bank_name
 * @property string|null $bic
 * @property-read TenantInviteLink|null $inviteLink
 * @property TenantSettings $settings
 *
 * @method static \Database\Factories\TenantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereBankRecipientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereBankRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereBic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereSettings($value)
 *
 * @mixin \Eloquent
 */
final class Tenant extends Model
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'city',
        'country',
        'email',
        'iban',
        'bank_recipient',
        'bank_name',
        'bic',
        'settings',
    ];

    protected $casts = [
        'settings' => AsTenantSettings::class,
    ];

    public static function default(): static
    {
        return self::firstWhere('slug', config('setup.tenant.slug'));
    }

    public function setSlugAttribute(?string $value): void
    {
        if ($value !== null) {
            $this->attributes['slug'] = Str::lower($value);
        }
    }

    /**
     * Primary members — users who belong to this tenant via users.tenant_id.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function inviteLink(): HasOne
    {
        return $this->hasOne(TenantInviteLink::class);
    }

    public function settings(): TenantSettings
    {
        return $this->settings;
    }

    protected static function boot(): void
    {
        parent::boot();

        self::observe(TenantObserver::class);

        self::creating(function (Tenant $tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = Str::slug($tenant->name);
            }
        });
    }
}

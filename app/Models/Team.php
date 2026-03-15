<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TenantObserver;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tenant extends Model
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
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::observe(TenantObserver::class);

        static::creating(function (Tenant $tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = Str::slug($tenant->name);
            }
        });
    }

    public function setSlugAttribute(string $value): void
    {
        $this->attributes['slug'] = Str::lower($value);
    }

    /**
     * Primary members — users who belong to this tenant via users.tenant_id.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * All members including temporary ones — used for permission checks across the app.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')->withPivot('role')->withTimestamps();
    }
}

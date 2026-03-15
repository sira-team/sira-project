<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TeamObserver;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
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

        static::observe(TeamObserver::class);

        static::creating(function (Team $team) {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name);
            }
        });
    }

    public function setSlugAttribute(string $value): void
    {
        $this->attributes['slug'] = Str::lower($value);
    }

    /**
     * Primary members — users who belong to this team via users.team_id.
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
        return $this->belongsToMany(User::class, 'team_user')->withPivot('role')->withTimestamps();
    }
}

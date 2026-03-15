<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Participant> $participants
 * @property-read int|null $participants_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor query()
 * @method static \Database\Factories\VisitorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class Visitor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\HostelFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, HostelRoom> $rooms
 * @property-read int|null $rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CampContract> $contracts
 * @property-read int|null $contracts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hostel withoutTrashed()
 *
 * @mixin \Eloquent
 */
final class Hostel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
        'website',
        'notes',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(CampContract::class);
    }

    protected static function newFactory(): HostelFactory
    {
        return HostelFactory::new();
    }
}

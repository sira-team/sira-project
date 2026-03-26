<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Camp\Database\Factories\CampContractFactory;

/**
 * @property int $id
 * @property int $camp_id
 * @property int $hostel_id
 * @property float $price_per_person_per_night
 * @property bool $includes_catering
 * @property int $contracted_beds
 * @property Carbon|null $contract_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Hostel|null $hostel
 * @property-read Camp|null $camp
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereCateringIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereContractedParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereContractedSupporters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereHostelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract wherePricePerPersonPerNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereUpdatedAt($value)
 * @method static \Modules\Camp\Database\Factories\CampContractFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereContractedBeds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CampContract whereIncludesCatering($value)
 *
 * @mixin \Eloquent
 */
final class CampContract extends Model
{
    use HasFactory;

    protected $table = 'camp_contracts';

    protected $fillable = [
        'camp_id',
        'hostel_id',
        'price_per_person_per_night',
        'includes_catering',
        'contracted_beds',
        'contract_date',
        'notes',
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    protected static function newFactory(): CampContractFactory
    {
        return CampContractFactory::new();
    }

    protected function casts(): array
    {
        return [
            'price_per_person_per_night' => 'decimal:2',
            'includes_catering' => 'boolean',
            'contract_date' => 'date',
        ];
    }
}

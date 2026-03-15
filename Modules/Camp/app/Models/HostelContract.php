<?php

declare(strict_types=1);

namespace Modules\Camp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $hostel_id
 * @property int|null $camp_id
 * @property float $price_per_person_per_night
 * @property int $contracted_participants
 * @property int $contracted_supporters
 * @property Carbon|null $contract_date
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Hostel|null $hostel
 * @property-read Camp|null $camp
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereCampId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereContractedParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereContractedSupporters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereHostelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract wherePricePerPersonPerNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HostelContract whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class HostelContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'camp_id',
        'price_per_person_per_night',
        'contracted_participants',
        'contracted_supporters',
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

    protected function casts(): array
    {
        return [
            'price_per_person_per_night' => 'decimal:2',
            'contract_date' => 'date',
        ];
    }
}

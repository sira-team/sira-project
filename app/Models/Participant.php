<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $visitor_id
 * @property string $name
 * @property Carbon $date_of_birth
 * @property string $gender
 * @property bool $is_self
 * @property string|null $allergies
 * @property string|null $medications
 * @property string|null $medical_notes
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Visitor|null $visitor
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant query()
 * @method static \Database\Factories\ParticipantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereIsSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereMedicalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereMedications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Participant whereVisitorId($value)
 *
 * @mixin \Eloquent
 */
final class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'name',
        'date_of_birth',
        'gender',
        'is_self',
        'allergies',
        'medications',
        'medical_notes',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'is_self' => 'boolean',
        ];
    }
}

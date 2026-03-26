<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use Database\Factories\VisitorFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $allergies
 * @property string|null $medications
 * @property string|null $medical_notes
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Visitor> $guardians
 * @property-read int|null $guardians_count
 * @property-read Collection<int, Visitor> $children
 * @property-read int|null $children_count
 *
 * @method static VisitorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereUpdatedAt($value)
 *
 * @property-read VisitorChild|null $pivot
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereMedicalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereMedications($value)
 *
 * @mixin \Eloquent
 */
final class Visitor extends Model
{
    /** @use HasFactory<VisitorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'allergies',
        'medications',
        'medical_notes',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    /**
     * The adults who are responsible for this visitor (child).
     */
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'visitor_children', 'child_id', 'parent_id')
            ->withPivot('relationship')
            ->using(VisitorChild::class);
    }

    /**
     * The child visitors this visitor is responsible for.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'visitor_children', 'parent_id', 'child_id')
            ->withPivot('relationship')
            ->using(VisitorChild::class);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
        ];
    }
}

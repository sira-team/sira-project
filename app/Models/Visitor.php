<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use Database\Factories\VisitorFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\VisitorStatus;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $allergies
 * @property string|null $medications
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 *
 * @mixin \Eloquent
 */
final class Visitor extends Model
{
    /** @use HasFactory<VisitorFactory> */
    use HasFactory;

    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'allergies',
        'medications',
    ];

    public static function participatingStatuses(): array
    {
        return [VisitorStatus::Pending->value, VisitorStatus::Confirmed->value];
    }

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

    /**
     * Determine where to send the mail notification.
     */
    public function routeNotificationForMail($notification): array
    {
        $emails = $this->guardians()->pluck('email')->filter()->all();

        if ($this->email) {
            $emails[] = $this->email;
        }

        return array_unique($emails);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
        ];
    }
}

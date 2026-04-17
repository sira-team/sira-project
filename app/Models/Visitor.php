<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use Database\Factories\VisitorFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property Gender|null $gender
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Visitor> $guardians
 * @property-read int|null $guardians_count
 * @property-read Collection<int, Visitor> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Camp> $camps
 * @property-read int|null $camps_count
 * @property-read Visitor|null $guardian
 * @property-read Collection<int, VisitorChild> $parentRelations
 * @property-read int|null $parent_relations_count
 * @property-read VisitorChild|null $pivot
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visitor whereGender($value)
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
        'gender',
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
     * Direct relation to the pivot records where this visitor is the child.
     */
    public function parentRelations(): HasMany
    {
        return $this->hasMany(VisitorChild::class, 'child_id');
    }

    /**
     * The first guardian of this visitor, resolved through the pivot.
     */
    public function guardian(): HasOneThrough
    {
        return $this->hasOneThrough(
            self::class,
            VisitorChild::class,
            'child_id',
            'id',
            'id',
            'parent_id',
        );
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

    public function camps(): BelongsToMany
    {
        return $this->belongsToMany(Camp::class, 'camp_visitor');
    }

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GuardianRelationship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $parent_id
 * @property int $child_id
 * @property GuardianRelationship $relationship
 * @property-read Visitor $parent
 * @property-read Visitor $child
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisitorChild whereRelationship($value)
 *
 * @mixin \Eloquent
 */
final class VisitorChild extends Pivot
{
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'visitor_children';

    protected $fillable = [
        'parent_id',
        'child_id',
        'relationship',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'parent_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'child_id');
    }

    protected function casts(): array
    {
        return [
            'relationship' => GuardianRelationship::class,
        ];
    }
}

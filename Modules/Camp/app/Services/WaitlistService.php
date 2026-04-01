<?php

declare(strict_types=1);

namespace Modules\Camp\Services;

use App\Enums\Gender;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Camp\Actions\TransitionCampVisitor;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\CampTargetGroup;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

final class WaitlistService
{
    public function expire(Camp $camp): int
    {
        /** @var Collection<int, CampVisitor> $expired */
        $expired = CampVisitor::query()
            ->with(['visitor.guardians'])
            ->where('camp_id', $camp->id)
            ->where('status', VisitorStatus::Pending->value)
            ->whereDate('registered_at', '<=', today()->subDays(7))
            ->get();

        $expired->each(fn (CampVisitor $visitor) => TransitionCampVisitor::dispatch($visitor, VisitorStatus::Waitlisted, CampNotificationType::Waitlisted));

        return $expired->count();
    }

    public function promote(Camp $camp): void
    {
        /** @var Collection<int, CampVisitor> $waiting */
        $waiting = $camp->campVisitors()
            ->with('visitor')
            ->where('status', VisitorStatus::Waitlisted)
            ->orderBy('waitlist_position')
            ->get();

        if ($waiting->isEmpty()) {
            return;
        }

        if ($camp->target_group === CampTargetGroup::Family) {
            $capacity = $this->getCapacityForGender($camp, Gender::Male);
            $waiting->take(max(0, $capacity))->each(fn (CampVisitor $visitor) => TransitionCampVisitor::run($visitor, VisitorStatus::Pending, CampNotificationType::WaitlistPromoted));

            return;
        }

        foreach ([Gender::Male, Gender::Female] as $gender) {
            $capacity = $this->getCapacityForGender($camp, $gender);
            $waiting
                ->filter(fn (CampVisitor $campVisitor) => $campVisitor->visitor->gender === $gender)
                ->take(max(0, $capacity))
                ->each(fn (CampVisitor $visitor) => TransitionCampVisitor::run($visitor, VisitorStatus::Pending, CampNotificationType::WaitlistPromoted));
        }
    }

    public function assignPosition(Camp $camp, Gender $gender): int
    {
        return ($camp->campVisitors()
            ->where('status', VisitorStatus::Waitlisted)
            ->when($camp->target_group !== CampTargetGroup::Family, function (Builder $query) use ($gender) {
                return $query->whereHas('visitor', function (Builder $query) use ($gender) {
                    $query->where('gender', $gender);
                });
            })
            ->max('waitlist_position') ?? 0) + 1;
    }

    public function getCapacityForGender(Camp $camp, Gender $gender): int
    {
        /** @var array<string> $activeStatuses */
        $activeStatuses = Visitor::participatingStatuses();

        if ($camp->target_group === CampTargetGroup::Family) {
            return $camp->max_visitors_all - $camp->visitors()->wherePivotIn('status', $activeStatuses)->count();
        }

        return match ($gender) {
            Gender::Male => $camp->max_visitors_male - $camp->visitors()->where('gender', Gender::Male)->wherePivotIn('status', $activeStatuses)->count(),
            Gender::Female => $camp->max_visitors_female - $camp->visitors()->where('gender', Gender::Female)->wherePivotIn('status', $activeStatuses)->count(),
        };
    }

    public function capacityReached(Camp $camp, Visitor $visitor): bool
    {
        return $this->getCapacityForGender($camp, $visitor->gender) <= 0;
    }
}

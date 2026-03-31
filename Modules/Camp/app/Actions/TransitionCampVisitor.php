<?php

declare(strict_types=1);

namespace Modules\Camp\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Services\WaitlistService;

final class TransitionCampVisitor
{
    use AsAction;

    public function __construct(
        private readonly WaitlistService $waitlistService,
    ) {}

    public function handle(CampVisitor $visitor, VisitorStatus $status, CampNotificationType $notificationType): void
    {
        if ($status === VisitorStatus::Waitlisted) {
            $position = $this->waitlistService->assignPosition($visitor->camp, $visitor->visitor->gender);
            $visitor->update(['waitlist_position' => $position, 'status' => $status]);
        } else {
            $visitor->update(['status' => $status]);
        }

        $visitor->notify($notificationType);
    }
}

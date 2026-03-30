<?php

declare(strict_types=1);

namespace Modules\Camp\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\CampVisitor;

final class TransitionCampVisitor
{
    use AsAction;

    public function handle(CampVisitor $visitor, VisitorStatus $status, CampNotificationType $notificationType): void
    {
        $visitor->update(['status' => $status]);

        $visitor->notify($notificationType);
    }
}

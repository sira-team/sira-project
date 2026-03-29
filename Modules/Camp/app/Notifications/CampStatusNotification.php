<?php

declare(strict_types=1);

namespace Modules\Camp\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Camp\Enums\CampNotificationType;
use Modules\Camp\Mails\CampTemplateMail;
use Modules\Camp\Models\CampEmailTemplate;
use Modules\Camp\Models\CampVisitor;

final class CampStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CampNotificationType $type,
        public readonly CampVisitor $campVisitor
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): CampTemplateMail
    {
        $template = CampEmailTemplate::withoutGlobalScopes()
            ->where('tenant_id', $this->campVisitor->camp->tenant_id)
            ->where('key', $this->type->value)
            ->firstOrFail();

        return (new CampTemplateMail($template, $this->campVisitor))
            ->to($notifiable->routeNotificationForMail($this));
    }
}

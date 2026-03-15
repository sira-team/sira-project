<?php

declare(strict_types=1);

namespace Modules\Expo\Observers;

use Illuminate\Support\Facades\Mail;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Mails\ExpoRequestAcceptedMail;
use Modules\Expo\Mails\ExpoRequestConfirmationMail;
use Modules\Expo\Mails\ExpoRequestDeclinedMail;
use Modules\Expo\Mails\ExpoRequestInternalNotificationMail;
use Modules\Expo\Models\ExpoRequest;

class ExpoRequestObserver
{
    public function created(ExpoRequest $expoRequest): void
    {
        Mail::queue(new ExpoRequestConfirmationMail($expoRequest));
        Mail::queue(new ExpoRequestInternalNotificationMail($expoRequest));
    }

    public function updated(ExpoRequest $expoRequest): void
    {
        if (! $expoRequest->isDirty('status')) {
            return;
        }

        $newStatus = $expoRequest->status;

        if ($newStatus === ExpoRequestStatus::Accepted) {
            Mail::queue(new ExpoRequestAcceptedMail($expoRequest));
        } elseif ($newStatus === ExpoRequestStatus::Declined) {
            Mail::queue(new ExpoRequestDeclinedMail($expoRequest));
        }
    }
}

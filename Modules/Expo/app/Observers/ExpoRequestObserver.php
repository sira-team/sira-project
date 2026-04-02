<?php

declare(strict_types=1);

namespace Modules\Expo\Observers;

use Illuminate\Support\Facades\Mail;
use Modules\Expo\Mails\ExpoRequestReceivedMail;
use Modules\Expo\Models\ExpoRequest;

final class ExpoRequestObserver
{
    public function created(ExpoRequest $expoRequest): void
    {
        Mail::queue(new ExpoRequestReceivedMail($expoRequest));
    }
}

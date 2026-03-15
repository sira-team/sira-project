<?php

declare(strict_types=1);

namespace Modules\Expo\Enums;

enum ExpoStatus: string
{
    case Planned = 'planned';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}

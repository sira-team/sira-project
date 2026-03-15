<?php

declare(strict_types=1);

namespace Modules\Expo\Enums;

enum ExpoRequestStatus: string
{
    case New = 'new';
    case InReview = 'in_review';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Completed = 'completed';
}

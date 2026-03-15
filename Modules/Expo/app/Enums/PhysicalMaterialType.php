<?php

declare(strict_types=1);

namespace Modules\Expo\Enums;

enum PhysicalMaterialType: string
{
    case Miniature = 'miniature';
    case Poster = 'poster';
    case VideoScreen = 'video_screen';
    case Other = 'other';
}

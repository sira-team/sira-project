<?php

declare(strict_types=1);

namespace Modules\Expo\Enums;

enum PhysicalMaterialType: string
{
    case Miniature = 'miniature';
    case Poster = 'poster';
    case Other = 'other';
}

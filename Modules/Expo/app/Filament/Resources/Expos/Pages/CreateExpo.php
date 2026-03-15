<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Expo\Filament\Resources\Expos\ExpoResource;

class CreateExpo extends CreateRecord
{
    protected static string $resource = ExpoResource::class;
}

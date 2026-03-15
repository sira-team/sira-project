<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Expo\Filament\Resources\Stations\StationResource;

final class CreateStation extends CreateRecord
{
    protected static string $resource = StationResource::class;
}

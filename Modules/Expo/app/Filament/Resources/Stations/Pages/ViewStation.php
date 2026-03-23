<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Expo\Filament\Resources\Stations\StationResource;

final class ViewStation extends ViewRecord
{
    protected static string $resource = StationResource::class;
}

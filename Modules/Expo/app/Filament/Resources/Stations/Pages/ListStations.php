<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Expo\Filament\Resources\Stations\StationResource;

class ListStations extends ListRecords
{
    protected static string $resource = StationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

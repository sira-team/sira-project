<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Expo\Filament\Resources\ExpoRequests\ExpoRequestResource;

class ListExpoRequests extends ListRecords
{
    protected static string $resource = ExpoRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

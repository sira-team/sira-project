<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Expo\Filament\Resources\Expos\ExpoResource;

class ListExpos extends ListRecords
{
    protected static string $resource = ExpoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

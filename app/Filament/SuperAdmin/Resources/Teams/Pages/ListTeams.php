<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Teams\Pages;

use App\Filament\SuperAdmin\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

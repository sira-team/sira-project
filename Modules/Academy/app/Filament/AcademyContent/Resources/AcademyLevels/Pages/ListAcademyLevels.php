<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\AcademyLevelResource;

class ListAcademyLevels extends ListRecords
{
    protected static string $resource = AcademyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

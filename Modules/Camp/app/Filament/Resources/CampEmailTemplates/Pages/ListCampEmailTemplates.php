<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampEmailTemplates\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Camp\Filament\Resources\CampEmailTemplates\CampEmailTemplateResource;

final class ListCampEmailTemplates extends ListRecords
{
    protected static string $resource = CampEmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

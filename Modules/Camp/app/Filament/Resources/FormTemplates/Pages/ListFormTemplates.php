<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\FormTemplates\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Camp\Filament\Resources\FormTemplates\FormTemplateResource;

final class ListFormTemplates extends ListRecords
{
    protected static string $resource = FormTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

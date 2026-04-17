<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\FormTemplates\Pages;

use App\Filament\GlobalAdmin\Resources\FormTemplates\FormTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

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

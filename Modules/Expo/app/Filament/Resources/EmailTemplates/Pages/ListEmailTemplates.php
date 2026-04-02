<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\EmailTemplates\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Expo\Filament\Resources\EmailTemplates\EmailTemplateResource;

final class ListEmailTemplates extends ListRecords
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

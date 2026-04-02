<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\EmailTemplates\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Camp\Filament\Resources\EmailTemplates\EmailTemplateResource;

final class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

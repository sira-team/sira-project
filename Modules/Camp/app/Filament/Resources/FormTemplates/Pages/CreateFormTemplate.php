<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\FormTemplates\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Camp\Filament\Resources\FormTemplates\FormTemplateResource;

final class CreateFormTemplate extends CreateRecord
{
    protected static string $resource = FormTemplateResource::class;
}

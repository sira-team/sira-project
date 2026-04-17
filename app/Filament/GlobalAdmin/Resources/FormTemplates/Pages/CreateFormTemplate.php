<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\FormTemplates\Pages;

use App\Filament\GlobalAdmin\Resources\FormTemplates\FormTemplateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateFormTemplate extends CreateRecord
{
    protected static string $resource = FormTemplateResource::class;
}

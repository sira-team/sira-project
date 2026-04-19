<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\FormTemplates\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Camp\Filament\Resources\FormTemplates\FormTemplateResource;
use Modules\Camp\Models\FormTemplate;

final class EditFormTemplate extends EditRecord
{
    protected static string $resource = FormTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (FormTemplate $record): bool => $record->camps()->exists())
                ->tooltip(fn (FormTemplate $record): ?string => $record->camps()->exists()
                    ? __('Cannot delete a template that is linked to one or more camps.')
                    : null
                ),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Camp\Filament\Resources\Camps\CampResource;

final class EditCamp extends EditRecord
{
    protected static string $resource = CampResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['registration_opens_at']) {
            $data['registration_opens_at'] = $data['registration_opens_at'].' 00:00:00';
        }

        if ($data['registration_ends_at']) {
            $data['registration_ends_at'] = $data['registration_ends_at'].' 23:59:59';
        }

        return parent::mutateFormDataBeforeSave($data);
    }
}

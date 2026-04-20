<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Modules\Camp\Filament\Resources\Camps\CampResource;

final class EditCamp extends EditRecord
{
    protected static string $resource = CampResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

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
            $data['registration_opens_at'] = Carbon::createFromFormat(
                'Y-m-d',
                $data['registration_opens_at']
            )->startOfDay();
        }

        if ($data['registration_ends_at']) {
            $data['registration_ends_at'] = Carbon::createFromFormat(
                'Y-m-d',
                $data['registration_ends_at']
            )->endOfDay();
        }

        return parent::mutateFormDataBeforeSave($data);
    }
}

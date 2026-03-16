<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Camp\Filament\Resources\Hostels\HostelResource;

final class EditHostel extends EditRecord
{
    protected static string $resource = HostelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

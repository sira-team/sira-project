<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Camp\Filament\Resources\Hostels\HostelResource;

final class ListHostels extends ListRecords
{
    protected static string $resource = HostelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

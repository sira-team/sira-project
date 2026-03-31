<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Camp\Filament\Resources\Camps\CampResource;

final class ListCamps extends ListRecords
{
    protected static string $resource = CampResource::class;

    public static function getNavigationLabel(): string
    {
        return __('Camps');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

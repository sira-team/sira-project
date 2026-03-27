<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Widgets\CampCostCalculatorWidget;

final class ViewCamp extends ViewRecord
{
    protected static string $resource = CampResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    public static function getNavigationLabel(): string
    {
        return __('Details');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CampCostCalculatorWidget::class,
        ];
    }
}

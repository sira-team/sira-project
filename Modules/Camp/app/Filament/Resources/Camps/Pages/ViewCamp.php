<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Widgets\CampCostCalculatorWidget;

final class ViewCamp extends ViewRecord
{
    protected static string $resource = CampResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            CampCostCalculatorWidget::class,
        ];
    }
}

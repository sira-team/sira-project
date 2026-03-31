<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Widgets\CampCostCalculatorWidget;
use Modules\Camp\Models\Camp;

/**
 * @property Camp $record
 */
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
            Action::make('register')
                ->label(__('Register Link'))
                ->color(Color::Slate)
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->openUrlInNewTab()
                ->url(fn () => route('camp.register.show', ['camp' => $this->record, 'tenant' => $this->record->tenant]))
                ->visible(fn () => $this->record->registration_is_open),
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

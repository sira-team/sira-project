<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampExpenses\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Filament\Resources\CampExpenses\CampExpenseResource;
use Modules\Camp\Filament\Resources\Concerns\HasCampSubNavigation;

final class ListCampExpenses extends ListRecords
{
    use HasCampSubNavigation;
    use InteractsWithParentRecord;

    protected static string $resource = CampExpenseResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function getNavigationLabel(): string
    {
        return __('Expenses');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

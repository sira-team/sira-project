<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Pages;

use BackedEnum;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Filament\Resources\CampVisitors\CampVisitorResource;
use Modules\Camp\Filament\Resources\Concerns\HasCampSubNavigation;

final class ListCampVisitors extends ListRecords
{
    use HasCampSubNavigation;
    use InteractsWithParentRecord;

    protected static string $resource = CampVisitorResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    public static function getNavigationLabel(): string
    {
        return __('Visitors');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All')),
            'confirmed' => Tab::make(__('Confirmed'))
                ->modifyQueryUsing(fn ($query) => $query->where('status', VisitorStatus::Confirmed)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

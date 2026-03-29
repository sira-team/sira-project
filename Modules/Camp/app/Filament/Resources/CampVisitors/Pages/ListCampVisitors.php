<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Pages;

use BackedEnum;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
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
            VisitorStatus::Pending->value => Tab::make(__('Pending'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Pending)),
            VisitorStatus::Waitlisted->value => Tab::make(__('Waitlisted'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Waitlisted)),
            VisitorStatus::Confirmed->value => Tab::make(__('Confirmed'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Confirmed)),
            VisitorStatus::Paid->value => Tab::make(__('Paid'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Paid)),
            VisitorStatus::Cancelled->value => Tab::make(__('Cancelled'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Cancelled)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

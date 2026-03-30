<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Pages;

use BackedEnum;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Filament\Resources\CampVisitors\CampVisitorResource;
use Modules\Camp\Filament\Resources\Concerns\HasCampSubNavigation;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;

/**
 * @property Camp $parentRecord
 * @property-read Collection $statusCounts
 */
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

    #[Computed]
    public function statusCounts(): Collection
    {
        return CampVisitor::query()
            ->where('camp_id', $this->parentRecord->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All'))
                ->icon(Heroicon::OutlinedUsers)
                ->badge(fn () => $this->statusCounts->sum())
                ->badgeColor(Color::Gray),

            VisitorStatus::Pending->value => Tab::make(__('Pending'))
                ->icon(Heroicon::OutlinedClock)
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Pending->value, 0))
                ->badgeColor(VisitorStatus::Pending->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Pending)),

            VisitorStatus::Waitlisted->value => Tab::make(__('Waitlisted'))
                ->icon(Heroicon::OutlinedQueueList)
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Waitlisted->value, 0))
                ->badgeColor(VisitorStatus::Waitlisted->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Waitlisted)),

            VisitorStatus::Confirmed->value => Tab::make(__('Confirmed'))
                ->icon(Heroicon::OutlinedCheckCircle)
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Confirmed->value, 0))
                ->badgeColor(VisitorStatus::Confirmed->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Confirmed)),

            VisitorStatus::Cancelled->value => Tab::make(__('Cancelled'))
                ->icon(Heroicon::OutlinedXCircle)
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Cancelled->value, 0))
                ->badgeColor(VisitorStatus::Cancelled->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Cancelled)),
        ];
    }

    public function updatedActiveTab(): void
    {
        unset($this->statusCounts);
        $this->resetTable();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

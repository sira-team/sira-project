<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Pages;

use App\Filament\Exports\CampVisitorExporter;
use App\Filament\Exports\CampVisitorWithGuardianExporter;
use BackedEnum;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\Concerns\InteractsWithParentRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Modules\Camp\Enums\CampTargetGroup;
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
                ->icon(Heroicon::OutlinedUserGroup)
                ->badge(fn () => $this->statusCounts->sum())
                ->badgeColor(Color::Gray),

            VisitorStatus::Pending->value => Tab::make(__('Pending'))
                ->icon(VisitorStatus::Pending->getIcon())
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Pending->value, 0))
                ->badgeColor(VisitorStatus::Pending->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Pending)),

            VisitorStatus::Waitlisted->value => Tab::make(__('Waitlisted'))
                ->icon(VisitorStatus::Waitlisted->getIcon())
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Waitlisted->value, 0))
                ->badgeColor(VisitorStatus::Waitlisted->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Waitlisted)),

            VisitorStatus::Confirmed->value => Tab::make(__('Confirmed'))
                ->icon(VisitorStatus::Confirmed->getIcon())
                ->badge(fn () => $this->statusCounts->get(VisitorStatus::Confirmed->value, 0))
                ->badgeColor(VisitorStatus::Confirmed->getColor())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', VisitorStatus::Confirmed)),

            VisitorStatus::Cancelled->value => Tab::make(__('Cancelled'))
                ->icon(VisitorStatus::Cancelled->getIcon())
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
        return [
            ExportAction::make('export')
                ->label(__('Export'))
                ->icon(Heroicon::OutlinedArrowDownOnSquare)
                ->color('primary')
                ->exporter(CampVisitorExporter::class)
                ->modifyQueryUsing(fn () => CampVisitor::query()
                    ->where('camp_id', $this->parentRecord->id)
                    ->where('status', VisitorStatus::Confirmed->value)
                    ->with(['visitor', 'room'])
                    ->leftJoin('visitors', 'camp_visitor.visitor_id', '=', 'visitors.id')
                    ->leftJoin('hostel_rooms', 'camp_visitor.room_id', '=', 'hostel_rooms.id')
                    ->orderBy('visitors.gender')
                    ->orderBy('hostel_rooms.floor')
                    ->orderBy('hostel_rooms.name')
                    ->select('camp_visitor.*')
                )
                ->visible(fn (): bool => match ($this->parentRecord->target_group) {
                    CampTargetGroup::Children, CampTargetGroup::Teenagers => false,
                    default => true,
                }),
            ExportAction::make('exportWithGuardians')
                ->icon(Heroicon::OutlinedArrowDownOnSquare)
                ->color('primary')
                ->label(__('Export'))
                ->exporter(CampVisitorWithGuardianExporter::class)
                ->modifyQueryUsing(fn () => CampVisitor::query()
                    ->where('camp_id', $this->parentRecord->id)
                    ->where('status', VisitorStatus::Confirmed->value)
                    ->with(['visitor.guardian', 'room'])
                    ->leftJoin('visitors', 'camp_visitor.visitor_id', '=', 'visitors.id')
                    ->leftJoin('hostel_rooms', 'camp_visitor.room_id', '=', 'hostel_rooms.id')
                    ->orderBy('visitors.gender')
                    ->orderBy('hostel_rooms.floor')
                    ->orderBy('hostel_rooms.name')
                    ->select('camp_visitor.*')
                )
                ->visible(fn (): bool => match ($this->parentRecord->target_group) {
                    CampTargetGroup::Children, CampTargetGroup::Teenagers => true,
                    default => false,
                }),
        ];
    }
}

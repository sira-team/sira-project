<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Tables;

use App\Enums\Gender;
use App\Enums\NotificationType;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Camp\Actions\TransitionCampVisitor;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\HostelRoom;
use Modules\Camp\Services\WaitlistService;

final class CampVisitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferFilters(false)
            ->columns([
                TextColumn::make('room.name')
                    ->label(__('Room'))
                    ->default('—')
                    ->description(fn (CampVisitor $record): string => $record->room
                        ? CampVisitor::query()->where('camp_id', $record->camp_id)->where('room_id', $record->room_id)->count().'/'.$record->room->capacity
                        : ''
                    )
                    ->sortable()
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Confirmed->value),
                TextColumn::make('visitor.name')
                    ->label(__('Visitor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitor.gender')
                    ->sortable()
                    ->label(__('Gender'))
                    ->badge(),
                TextColumn::make('visitor.date_of_birth')
                    ->label(__('Age'))
                    ->formatStateUsing(fn (Carbon $state): string => (string) $state->age),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === 'all'),
                TextColumn::make('registered_at')
                    ->label(__('Registered'))
                    ->dateTime('d.m.Y H:i')
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') !== VisitorStatus::Confirmed->value)
                    ->sortable(),
                TextColumn::make('wishes')
                    ->label(__('Wishes'))
                    ->columnSpan(2)
                    ->placeholder('—')
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Confirmed->value),
                TextColumn::make('waitlist_position')
                    ->label(__('Waitlist Pos'))
                    ->placeholder('—')
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Waitlisted->value),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->options(collect(Gender::cases())->mapWithKeys(fn (Gender $gender) => [$gender->value => $gender->getLabel()])->all())
                    ->label(__('Gender'))
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'],
                        fn (Builder $query, $value) => $query->whereHas('visitor', fn (Builder $query) => $query->where('gender', $value))
                    )),
                TernaryFilter::make('room_id')
                    ->hidden(fn ($livewire): bool => ($livewire->activeTab ?? 'all') !== VisitorStatus::Confirmed->value)
                    ->label(__('Room'))
                    ->nullable()
                    ->trueLabel(__('Assigned'))
                    ->falseLabel(__('Unassigned')),
            ])
            ->recordActions([
                Action::make('confirm')
                    ->label(__('Confirm'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (CampVisitor $record) => TransitionCampVisitor::run($record, VisitorStatus::Confirmed, NotificationType::CampConfirmed))
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Pending->value),

                Action::make('promote')
                    ->label(__('Promote to Pending'))
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (CampVisitor $record, WaitlistService $service): void {
                        TransitionCampVisitor::run($record, VisitorStatus::Pending, NotificationType::CampWaitlistPromoted);
                    })
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Waitlisted->value),

                Action::make('assignRoom')
                    ->label(__('Assign Room'))
                    ->icon('heroicon-o-home')
                    ->fillForm(fn (CampVisitor $record): array => ['room_id' => $record->room_id])
                    ->schema([
                        Grid::make()->columns(2)->schema([
                            Select::make('room_id')
                                ->label(__('Room'))
                                ->options(function (CampVisitor $record): array {
                                    $camp = $record->camp;

                                    if (! $camp->contract) {
                                        return [];
                                    }

                                    $occupancy = CampVisitor::query()
                                        ->where('camp_id', $camp->id)
                                        ->whereNotNull('room_id')
                                        ->groupBy('room_id')
                                        ->selectRaw('room_id, count(*) as count')
                                        ->pluck('count', 'room_id');

                                    $options = HostelRoom::query()
                                        ->availableForVisitors($camp)
                                        ->get()
                                        ->filter(fn (HostelRoom $room): bool => $occupancy->get($room->id) < $room->capacity)
                                        ->mapWithKeys(fn (HostelRoom $room): array => [
                                            $room->id => "{$room->name} · Floor {$room->floor} · {$occupancy->get($room->id)}/{$room->capacity}",
                                        ])
                                        ->all();

                                    if ($record->room_id) {
                                        if (array_key_exists($record->room_id, $options)) {
                                            $options[$record->room_id] .= ' (current)';
                                        } else {
                                            $current = HostelRoom::find($record->room_id);
                                            if ($current) {
                                                $options[$record->room_id] = "{$current->name} · Floor {$current->floor} · {$occupancy->get($current->id)}/{$current->capacity} (current)";
                                            }
                                        }
                                    }

                                    return $options;
                                })
                                ->required(),
                        ]),
                    ])
                    ->action(fn (CampVisitor $record, array $data) => $record->update(['room_id' => $data['room_id']]))
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Confirmed->value),

                Action::make('cancel')
                    ->label(__('Cancel'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (CampVisitor $record): void {
                        TransitionCampVisitor::run($record, VisitorStatus::Cancelled, NotificationType::CampCancelled);
                    })
                    ->visible(fn ($livewire): bool => in_array($livewire->activeTab ?? 'all', [
                        VisitorStatus::Pending->value,
                        VisitorStatus::Waitlisted->value,
                        VisitorStatus::Confirmed->value,
                    ])),

                Action::make('viewHealth')
                    ->label(__('Health Info'))
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->color(Color::Blue)
                    ->modal()
                    ->modalHeading(fn (CampVisitor $record) => __('Health Info').": {$record->visitor->name}")
                    ->schema([
                        Textarea::make('allergies')
                            ->label(__('Allergies'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->allergies),
                        Textarea::make('medications')
                            ->label(__('Medications'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->medications),
                    ])
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') !== 'all'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulkConfirm')
                        ->label(__('Confirm Selected'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(
                            fn (CampVisitor $record) => TransitionCampVisitor::run($record, VisitorStatus::Confirmed, NotificationType::CampConfirmed)
                        ))
                        ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Pending->value),

                    BulkAction::make('bulkCancel')
                        ->label(__('Cancel Selected'))
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(fn (CampVisitor $record) => TransitionCampVisitor::run($record, VisitorStatus::Cancelled, NotificationType::CampCancelled));
                        })
                        ->visible(fn ($livewire): bool => in_array($livewire->activeTab ?? 'all', [
                            VisitorStatus::Pending->value,
                            VisitorStatus::Waitlisted->value,
                            VisitorStatus::Confirmed->value,
                        ])),
                ]),
            ])
            ->toolbarActions([
                Action::make('swapRooms')
                    ->label(__('Swap Rooms'))
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema(fn ($livewire): array => [
                        Select::make('first_camp_visitor_id')
                            ->label(__('First Visitor'))
                            ->options(fn (): array => self::visitorOptionsWithRoom($livewire->getParentRecord()))
                            ->required(),
                        Select::make('second_camp_visitor_id')
                            ->label(__('Second Visitor'))
                            ->options(fn (): array => self::visitorOptionsWithRoom($livewire->getParentRecord()))
                            ->required(),
                    ])
                    ->visible(fn ($livewire): bool => ($livewire->activeTab ?? 'all') === VisitorStatus::Confirmed->value)
                    ->action(function (array $data): void {
                        $firstRoomId = CampVisitor::query()->where('id', $data['first_camp_visitor_id'])->value('room_id');
                        $secondRoomId = CampVisitor::query()->where('id', $data['second_camp_visitor_id'])->value('room_id');
                        CampVisitor::query()->where('id', $data['first_camp_visitor_id'])->update(['room_id' => $secondRoomId]);
                        CampVisitor::query()->where('id', $data['second_camp_visitor_id'])->update(['room_id' => $firstRoomId]);
                    }),
            ]);
    }

    private static function visitorOptionsWithRoom(Camp $camp): array
    {
        $rooms = CampVisitor::query()
            ->where('camp_id', $camp->id)
            ->whereNotNull('room_id')
            ->with('room')
            ->get()
            ->keyBy('id');

        return CampVisitor::query()
            ->where('camp_id', $camp->id)
            ->whereNotNull('room_id')
            ->with('visitor')
            ->get()
            ->mapWithKeys(fn (CampVisitor $cv): array => [
                $cv->id => $cv->visitor->name.' — '.($rooms->get($cv->id)?->room->name ?? '—'),
            ])
            ->all();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
            ->columns([
                TextColumn::make('visitor.name')
                    ->label(__('Visitor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitor.gender')
                    ->sortable()
                    ->label(__('Gender'))
                    ->badge(),
                TextColumn::make('registered_at')
                    ->label(__('Registered'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label(__('Room'))
                    ->default('—')
                    ->description(fn (CampVisitor $record): string => $record->room
                        ? CampVisitor::query()->where('camp_id', $record->camp_id)->where('room_id', $record->room_id)->count().'/'.$record->room->capacity
                        : ''
                    )
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('waitlist_position')
                    ->label(__('Waitlist Pos'))
                    ->visible(fn () => true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('confirm')
                    ->label(__('Confirm'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => VisitorStatus::Confirmed]);
                        // Queue CampConfirmedMail
                    })
                    ->visible(fn ($record) => $record->status === VisitorStatus::Pending),

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
                                        ->filter(fn (HostelRoom $room): bool => $occupancy->get($room->id, 0) < $room->capacity)
                                        ->mapWithKeys(fn (HostelRoom $room): array => [
                                            $room->id => "{$room->name} · Floor {$room->floor} · {$occupancy->get($room->id, 0)}/{$room->capacity}",
                                        ])
                                        ->all();

                                    if ($record->room_id) {
                                        if (array_key_exists($record->room_id, $options)) {
                                            $options[$record->room_id] .= ' (current)';
                                        } else {
                                            $current = HostelRoom::find($record->room_id);
                                            if ($current) {
                                                $options[$record->room_id] = "{$current->name} · Floor {$current->floor} · {$occupancy->get($current->id, 0)}/{$current->capacity} (current)";
                                            }
                                        }
                                    }

                                    return $options;
                                })
                                ->required(),
                        ]),
                    ])
                    ->action(fn (CampVisitor $record, array $data) => $record->update(['room_id' => $data['room_id']])),

                Action::make('markPaid')
                    ->label(__('Mark as Paid'))
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['payment_status' => VisitorStatus::Paid]);
                    })
                    ->visible(fn ($record) => $record->payment_status === VisitorStatus::Pending),

                Action::make('moveToWaitlist')
                    ->label(__('Move to Waitlist'))
                    ->icon('heroicon-o-list-bullet')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, WaitlistService $service) {
                        $record->update([
                            'status' => VisitorStatus::Waitlisted,
                            'waitlist_position' => $service->assignPosition($record->camp),
                        ]);
                    })
                    ->visible(fn ($record) => $record->status === VisitorStatus::Confirmed),

                Action::make('cancel')
                    ->label(__('Cancel'))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record, WaitlistService $service) {
                        $record->update(['status' => VisitorStatus::Cancelled]);
                        $service->promote($record->camp);
                        // Queue CampCancelledMail
                    })
                    ->visible(fn ($record) => $record->status !== VisitorStatus::Cancelled),

                Action::make('viewHealth')
                    ->label(__('Health Info'))
                    ->icon('heroicon-o-information-circle')
                    ->modal()
                    ->modalHeading(fn (CampVisitor $record) => "Health Info: {$record->visitor->name}")
                    ->schema([
                        Textarea::make('allergies')
                            ->label(__('Allergies'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->allergies),
                        Textarea::make('medications')
                            ->label(__('Medications'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->medications),
                        Textarea::make('medical_notes')
                            ->label(__('Medical Notes'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->medical_notes),
                        Textarea::make('emergency_contact')
                            ->label(__('Emergency Contact'))
                            ->disabled()
                            ->default(fn (CampVisitor $record) => "{$record->visitor->emergency_contact_name} / {$record->visitor->emergency_contact_phone}"),
                    ]),
            ])
            ->headerActions([
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

<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Modules\Camp\Enums\VisitorStatus;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\CampVisitorForm;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\HostelRoom;
use Modules\Camp\Services\WaitlistService;

/**
 * @property Camp $ownerRecord
 */
final class CampVisitorsRelationManager extends RelationManager
{
    protected static string $relationship = 'campVisitors';

    public function form(Schema $schema): Schema
    {
        return CampVisitorForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visitor.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitor.gender')
                    ->label('Gender')
                    ->badge(),
                TextColumn::make('registered_at')
                    ->label('Registered')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label('Room')
                    ->default('—')
                    ->description(fn (CampVisitor $record): string => $record->room
                        ? CampVisitor::query()->where('camp_id', $record->camp_id)->where('room_id', $record->room_id)->count().'/'.$record->room->capacity
                        : ''
                    )
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('waitlist_position')
                    ->label('Waitlist Pos')
                    ->visible(fn () => true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => VisitorStatus::Confirmed]);
                        // Queue CampConfirmedMail
                    })
                    ->visible(fn ($record) => $record->status === VisitorStatus::Pending),

                Action::make('assignRoom')
                    ->label('Assign Room')
                    ->icon('heroicon-o-home')
                    ->fillForm(fn (CampVisitor $record): array => ['room_id' => $record->room_id])
                    ->schema([
                        Select::make('room_id')
                            ->label('Room')
                            ->options(function (CampVisitor $record): array {
                                $camp = $this->ownerRecord;

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
                    ])
                    ->action(fn (CampVisitor $record, array $data) => $record->update(['room_id' => $data['room_id']])),

                Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['payment_status' => VisitorStatus::Paid]);
                    })
                    ->visible(fn ($record) => $record->payment_status === VisitorStatus::Pending),

                Action::make('moveToWaitlist')
                    ->label('Move to Waitlist')
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
                    ->label('Cancel')
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
                    ->label('Health Info')
                    ->icon('heroicon-o-information-circle')
                    ->modal()
                    ->modalHeading(fn (CampVisitor $record) => "Health Info: {$record->visitor->name}")
                    ->schema([
                        Textarea::make('allergies')
                            ->label('Allergies')
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->allergies),
                        Textarea::make('medications')
                            ->label('Medications')
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->medications),
                        Textarea::make('medical_notes')
                            ->label('Medical Notes')
                            ->disabled()
                            ->default(fn (CampVisitor $record) => $record->visitor->medical_notes),
                        Textarea::make('emergency_contact')
                            ->label('Emergency Contact')
                            ->disabled()
                            ->default(fn (CampVisitor $record) => "{$record->visitor->emergency_contact_name} / {$record->visitor->emergency_contact_phone}"),
                    ]),
            ])
            ->groupedBulkActions([
                BulkAction::make('confirmSelected')
                    ->label('Confirm Selected')
                    ->icon('heroicon-o-check')
                    ->action(function (Collection $records) {
                        $records->each(fn ($record) => $record->update(['status' => VisitorStatus::Confirmed]));
                    })
                    ->deselectRecordsAfterCompletion(),

                BulkAction::make('markSelectedPaid')
                    ->label('Mark Selected as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Collection $records) {
                        $records->each(fn ($record) => $record->update(['payment_status' => VisitorStatus::Paid]));
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public function canCreate(): bool
    {
        return false;
    }
}

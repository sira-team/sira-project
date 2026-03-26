<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Modules\Camp\Enums\CampRegistrationStatus;
use Modules\Camp\Models\CampVisitor;
use Modules\Camp\Models\HostelRoom;
use Modules\Camp\Services\WaitlistService;

final class CampRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('visitor.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('registered_at')
                    ->label('Registered')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (CampRegistrationStatus $state) => match ($state) {
                        CampRegistrationStatus::Pending => 'warning',
                        CampRegistrationStatus::Confirmed => 'success',
                        CampRegistrationStatus::Waitlisted => 'info',
                        CampRegistrationStatus::Cancelled => 'danger',
                        CampRegistrationStatus::Paid => throw new Exception('To be implemented'),
                    }),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (CampRegistrationStatus $state) => match ($state) {
                        CampRegistrationStatus::Pending => 'danger',
                        CampRegistrationStatus::Paid => 'success',
                        CampRegistrationStatus::Cancelled => throw new Exception('To be implemented'),
                        CampRegistrationStatus::Waitlisted => throw new Exception('To be implemented'),
                        CampRegistrationStatus::Confirmed => throw new Exception('To be implemented'),
                    }),
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
                        $record->update(['status' => CampRegistrationStatus::Confirmed]);
                        // Queue CampConfirmedMail
                    })
                    ->visible(fn ($record) => $record->status === CampRegistrationStatus::Pending),

                Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['payment_status' => CampRegistrationStatus::Paid]);
                    })
                    ->visible(fn ($record) => $record->payment_status === CampRegistrationStatus::Pending),

                Action::make('assignRoom')
                    ->label('Assign Room')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Select::make('hostel_room_id')
                            ->label('Room')
                            ->options(function (CampVisitor $record) {
                                $camp = $record->camp;
                                if (! $camp->contract) {
                                    return [];
                                }

                                return HostelRoom::query()
                                    ->where('hostel_id', $camp->contract->hostel_id)
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                    ])
                    ->action(function (CampVisitor $record, array $data) {
                        $record->update([
                            'room_id' => $data['hostel_room_id'],
                        ]);
                    }),

                Action::make('moveToWaitlist')
                    ->label('Move to Waitlist')
                    ->icon('heroicon-o-list-bullet')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, WaitlistService $service) {
                        $record->update([
                            'status' => CampRegistrationStatus::Waitlisted,
                            'waitlist_position' => $service->assignPosition($record->camp),
                        ]);
                    })
                    ->visible(fn ($record) => $record->status === CampRegistrationStatus::Confirmed),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record, WaitlistService $service) {
                        $record->update(['status' => CampRegistrationStatus::Cancelled]);
                        $service->promote($record->camp);
                        // Queue CampCancelledMail
                    })
                    ->visible(fn ($record) => $record->status !== CampRegistrationStatus::Cancelled),

                Action::make('viewHealth')
                    ->label('Health Info')
                    ->icon('heroicon-o-information-circle')
                    ->modal()
                    ->modalHeading(fn ($record) => "Health Info: {$record->participant->name}")
                    ->schema([
                        Textarea::make('allergies')
                            ->label('Allergies')
                            ->disabled()
                            ->default(fn ($record) => $record->participant->allergies),
                        Textarea::make('medications')
                            ->label('Medications')
                            ->disabled()
                            ->default(fn ($record) => $record->participant->medications),
                        Textarea::make('medical_notes')
                            ->label('Medical Notes')
                            ->disabled()
                            ->default(fn ($record) => $record->participant->medical_notes),
                        Textarea::make('emergency_contact')
                            ->label('Emergency Contact')
                            ->disabled()
                            ->default(fn ($record) => "{$record->participant->emergency_contact_name} / {$record->participant->emergency_contact_phone}"),
                    ]),
            ])
            ->groupedBulkActions([
                BulkAction::make('confirmSelected')
                    ->label('Confirm Selected')
                    ->icon('heroicon-o-check')
                    ->action(function (Collection $records) {
                        $records->each(fn ($record) => $record->update(['status' => CampRegistrationStatus::Confirmed]));
                    })
                    ->deselectRecordsAfterCompletion(),

                BulkAction::make('markSelectedPaid')
                    ->label('Mark Selected as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Collection $records) {
                        $records->each(fn ($record) => $record->update(['payment_status' => CampRegistrationStatus::Paid]));
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}

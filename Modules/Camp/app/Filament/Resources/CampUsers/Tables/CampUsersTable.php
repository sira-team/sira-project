<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampUsers\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampUser;
use Modules\Camp\Models\HostelRoom;

final class CampUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'room']))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Name')),
                TextColumn::make('user.gender')
                    ->sortable()
                    ->label(__('Gender'))
                    ->badge(),
                TextColumn::make('room_id')
                    ->label(__('Room'))
                    ->state(fn (CampUser $record): string => $record->room_id
                        ? ($record->room->name ?? '—')
                        : '—'
                    )
                    ->description(fn (CampUser $record): string => $record->room_id
                        ? CampUser::query()->where('camp_id', $record->camp_id)->where('room_id', $record->room_id)->count().'/'.($record->room->capacity ?? '?')
                        : ''
                    )
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('addStaff')
                    ->label(__('Add Staff Member'))
                    ->icon('heroicon-o-user-plus')
                    ->schema(fn ($livewire): array => [
                        Select::make('user_ids')
                            ->label(__('Staff Members'))
                            ->options(function () use ($livewire): array {
                                $camp = $livewire->getParentRecord();
                                $existing = CampUser::query()->where('camp_id', $camp->id)->pluck('user_id');

                                return User::query()
                                    ->where('tenant_id', $camp->tenant_id)
                                    ->whereNotIn('id', $existing)
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->multiple()
                            ->required(),
                    ])
                    ->action(function (array $data, $livewire): void {
                        $camp = $livewire->getParentRecord();
                        foreach ($data['user_ids'] as $userId) {
                            CampUser::create(['camp_id' => $camp->id, 'user_id' => $userId]);
                        }
                    }),
                Action::make('swapRooms')
                    ->label(__('Swap Rooms'))
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema(fn ($livewire): array => [
                        Select::make('first_user_id')
                            ->label(__('First Staff Member'))
                            ->options(fn (): array => self::staffOptionsWithRoom($livewire->getParentRecord()))
                            ->required(),
                        Select::make('second_user_id')
                            ->label(__('Second Staff Member'))
                            ->options(fn (): array => self::staffOptionsWithRoom($livewire->getParentRecord()))
                            ->required(),
                    ])
                    ->action(function (array $data, $livewire): void {
                        $camp = $livewire->getParentRecord();
                        $firstRoomId = CampUser::query()->where('camp_id', $camp->id)->where('user_id', $data['first_user_id'])->value('room_id');
                        $secondRoomId = CampUser::query()->where('camp_id', $camp->id)->where('user_id', $data['second_user_id'])->value('room_id');
                        CampUser::query()->where('camp_id', $camp->id)->where('user_id', $data['first_user_id'])->update(['room_id' => $secondRoomId]);
                        CampUser::query()->where('camp_id', $camp->id)->where('user_id', $data['second_user_id'])->update(['room_id' => $firstRoomId]);
                    }),
            ])
            ->recordActions([
                Action::make('assignRoom')
                    ->label(__('Assign Room'))
                    ->icon('heroicon-o-home')
                    ->fillForm(fn (CampUser $record): array => ['room_id' => $record->room_id])
                    ->schema(fn (CampUser $record, $livewire): array => [
                        Select::make('room_id')
                            ->label(__('Room'))
                            ->options(function () use ($record, $livewire): array {
                                /** @var Camp $camp */
                                $camp = $livewire->getParentRecord();

                                if (! $camp->contract) {
                                    return [];
                                }

                                $occupancy = CampUser::query()
                                    ->where('camp_id', $camp->id)
                                    ->whereNotNull('room_id')
                                    ->groupBy('room_id')
                                    ->selectRaw('room_id, count(*) as count')
                                    ->pluck('count', 'room_id');

                                $options = HostelRoom::query()
                                    ->availableForStaff($camp)
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
                    ->action(fn (CampUser $record, array $data) => $record->update(['room_id' => $data['room_id']])),
                DeleteAction::make(),
            ]);
    }

    private static function staffOptionsWithRoom(Camp $camp): array
    {
        $rooms = CampUser::query()
            ->where('camp_id', $camp->id)
            ->whereNotNull('room_id')
            ->with('room')
            ->get()
            ->keyBy('user_id');

        return $camp->users()
            ->get()
            ->mapWithKeys(fn (User $user): array => [
                $user->id => $user->name.' — '.($rooms->get($user->id)?->room->name),
            ])
            ->all();
    }
}

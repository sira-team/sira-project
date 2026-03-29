<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Camp\Models\Camp;
use Modules\Camp\Models\CampUser;
use Modules\Camp\Models\HostelRoom;

/**
 * @property Camp $ownerRecord
 */
final class CampUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('gender')
                    ->sortable()
                    ->label(__('Gender'))
                    ->badge(),
                TextColumn::make('pivot.room.name')
                    ->label(__('Room'))
                    ->default('—')
                    ->description(fn ($record): string => $record->pivot->room
                        ? CampUser::query()->where('camp_id', $record->pivot->camp_id)->where('room_id', $record->pivot->room_id)->count().'/'.$record->pivot->room->capacity
                        : ''
                    )
                    ->sortable(query: fn (Builder $query, string $direction) => $query
                        ->leftJoin('hostel_rooms', 'camp_user.room_id', '=', 'hostel_rooms.id')
                        ->orderBy('hostel_rooms.name', $direction)
                    ),
            ])
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordTitleAttribute('name'),
                Action::make('swapRooms')
                    ->label(__('Swap Rooms'))
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        Select::make('first_user_id')
                            ->label(__('First Staff Member'))
                            ->options(fn (): array => $this->staffOptionsWithRoom())
                            ->required(),
                        Select::make('second_user_id')
                            ->label(__('Second Staff Member'))
                            ->options(fn (): array => $this->staffOptionsWithRoom())
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        $camp = $this->ownerRecord;
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
                    ->fillForm(fn ($record): array => ['room_id' => $record->pivot->room_id])
                    ->schema([
                        Select::make('room_id')
                            ->label(__('Room'))
                            ->options(function ($record): array {
                                $camp = $this->ownerRecord;

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

                                $currentRoomId = $record->pivot->room_id;

                                if ($currentRoomId) {
                                    if (array_key_exists($currentRoomId, $options)) {
                                        $options[$currentRoomId] .= ' (current)';
                                    } else {
                                        $current = HostelRoom::find($currentRoomId);
                                        if ($current) {
                                            $options[$currentRoomId] = "{$current->name} · Floor {$current->floor} · {$occupancy->get($current->id, 0)}/{$current->capacity} (current)";
                                        }
                                    }
                                }

                                return $options;
                            })
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) => $record->pivot->update(['room_id' => $data['room_id']])),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    /** @return array<int, string> */
    private function staffOptionsWithRoom(): array
    {
        /** @var Collection<int, CampUser> $rooms */
        $rooms = CampUser::query()
            ->where('camp_id', $this->ownerRecord->id)
            ->whereNotNull('room_id')
            ->with('room')
            ->get()
            ->keyBy('user_id');

        return $this->ownerRecord->users()
            ->get()
            ->mapWithKeys(fn (User $user, int $id): array => [
                $id => $user->name.' — '.($rooms->get($id)?->room->name),
            ])
            ->all();
    }
}

<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\ExpoRequests\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Modules\Expo\Enums\ExpoRequestStatus;
use Modules\Expo\Enums\ExpoStatus;
use Modules\Expo\Filament\Resources\ExpoRequests\ExpoRequestResource;
use Modules\Expo\Models\Expo;
use Modules\Expo\Models\ExpoRequest;

/**
 * @property-read ExpoRequest $record
 */
final class EditExpoRequest extends EditRecord
{
    protected static string $resource = ExpoRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->createExpoAction(),
        ];
    }

    private function createExpoAction(): Action
    {
        return Action::make('createExpo')
            ->label(__('Create Expo from Request'))
            ->icon('heroicon-o-sparkles')
            ->visible(fn () => $this->record->status === ExpoRequestStatus::Accepted)
            ->schema([
                TextInput::make('name')
                    ->label(__('Expo Name'))
                    ->required()
                    ->default(fn () => $this->record->organisation_name),
                TextInput::make('location_name')
                    ->label(__('Location Name'))
                    ->required()
                    ->default(fn () => $this->record->city),
                TextInput::make('location_address')
                    ->label(__('Location Address')),
                DatePicker::make('date')
                    ->label(__('Expo Date'))
                    ->required()
                    ->default(fn () => $this->record->preferred_date_from),
                Textarea::make('notes')
                    ->label(__('Internal Notes'))
                    ->rows(3),
            ])
            ->action(function (array $data) {
                Expo::create([
                    ...$data,
                    'tenant_id' => $this->record->tenant_id,
                    'expo_request_id' => $this->record->id,
                    'status' => ExpoStatus::Planned,
                ]);

                Notification::make()
                    ->success()
                    ->title(__('Expo Created'))
                    ->body('Expo has been created successfully.')
                    ->send();
            });
    }
}

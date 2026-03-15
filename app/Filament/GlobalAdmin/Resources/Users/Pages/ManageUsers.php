<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Users\Pages;

use App\Enums\FeatureFlag;
use App\Filament\GlobalAdmin\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Schema;
use Laravel\Pennant\Feature;

final class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('assign-feature')
                ->label('Feature zuweisen')
                ->schema(function (Schema $schema) {
                    return $schema->schema([
                        Select::make('user')
                            ->label('Benutzer')
                            ->options(UserResource::getEloquentQuery()->pluck('name', 'id')),
                        Select::make('feature')
                            ->label('Feature')
                            ->options([
                                FeatureFlag::GlobalAdmin->value => FeatureFlag::GlobalAdmin->label(),
                                FeatureFlag::AcademyContentManagement->value => FeatureFlag::AcademyContentManagement->label(),
                            ]),
                    ]);
                })
                ->action(function (array $data) {
                    $user = UserResource::getEloquentQuery()->find($data['user']);
                    Feature::for($user)->activate(FeatureFlag::tryFrom($data['feature'])->value);

                    return true;
                }),
        ];
    }
}

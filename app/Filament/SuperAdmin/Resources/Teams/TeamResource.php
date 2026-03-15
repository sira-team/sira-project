<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources\Teams;

use App\Enums\Feature;
use App\Models\Team;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravel\Pennant\Feature as PennantFeature;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $navigationLabel = 'Tenants';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(Team::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                Select::make('country')
                    ->options([
                        'DE' => 'Germany',
                        'AT' => 'Austria',
                        'CH' => 'Switzerland',
                    ])
                    ->default('DE'),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('owner_email')
                    ->email()
                    ->maxLength(255)
                    ->visible(fn (string $operation) => $operation === 'create')
                    ->helperText('Email for the first tenant admin. They will receive an invitation.'),
                Section::make('Module Access')
                    ->description('Enable or disable modules for this tenant.')
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->components(
                        collect(Feature::teamFeatures())
                            ->map(fn (Feature $feature) => Toggle::make($feature->value)
                                ->label($feature->label())
                                ->helperText($feature->description())
                                ->afterStateHydrated(function (Toggle $component, Team $record) use ($feature) {
                                    $component->state(
                                        PennantFeature::for($record)->active($feature->value)
                                    );
                                })
                                ->dehydrated(false)
                            )
                            ->values()
                            ->toArray()
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->sortable(),
                TextColumn::make('city')
                    ->sortable(),
                TextColumn::make('country')
                    ->sortable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Members'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}

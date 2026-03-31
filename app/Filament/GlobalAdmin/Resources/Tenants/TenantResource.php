<?php

declare(strict_types=1);

namespace App\Filament\GlobalAdmin\Resources\Tenants;

use App\Enums\Country;
use App\Enums\FeatureFlag;
use App\Models\Tenant;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravel\Pennant\Feature as PennantFeature;

final class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $navigationLabel = 'Tenants';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->unique(Tenant::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('city')
                    ->label(__('City'))
                    ->maxLength(255),
                Select::make('country')
                    ->label(__('Country'))
                    ->options(Country::class)
                    ->default(Country::Germany->value),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(255),
                TextInput::make('owner_email')
                    ->label(__('Owner Email'))
                    ->email()
                    ->maxLength(255)
                    ->visible(fn (string $operation) => $operation === 'create')
                    ->helperText(__('Email for the first tenant admin. They will receive an invitation.')),
                Section::make(__('Module Access'))
                    ->description(__('Enable or disable modules for this tenant.'))
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->components(
                        collect(FeatureFlag::tenantFeatures())
                            ->map(fn (FeatureFlag $feature) => Toggle::make($feature->value)
                                ->label($feature->label())
                                ->helperText($feature->description())
                                ->afterStateHydrated(function (Toggle $component, ?Tenant $record) use ($feature) {
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
            ->deferFilters(false)
            ->columns([
                TextColumn::make('name')->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->sortable(),
                TextColumn::make('country')
                    ->label(__('Country'))
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label(__('Members')),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}

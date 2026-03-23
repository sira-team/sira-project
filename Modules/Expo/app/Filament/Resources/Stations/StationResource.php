<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Stations;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Expo\Filament\Resources\Stations\Pages\CreateStation;
use Modules\Expo\Filament\Resources\Stations\Pages\EditStation;
use Modules\Expo\Filament\Resources\Stations\Pages\ListStations;
use Modules\Expo\Filament\Resources\Stations\Pages\ViewStation;
use Modules\Expo\Filament\Resources\Stations\Schemas\StationForm;
use Modules\Expo\Filament\Resources\Stations\Schemas\StationInfolist;
use Modules\Expo\Filament\Resources\Stations\Tables\StationsTable;
use Modules\Expo\Models\Station;

final class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('ViewAny:Station') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return StationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StationsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StationInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStations::route('/'),
            'create' => CreateStation::route('/create'),
            'view' => ViewStation::route('/{record}'),
            'edit' => EditStation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

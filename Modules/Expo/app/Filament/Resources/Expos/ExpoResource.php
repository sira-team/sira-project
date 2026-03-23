<?php

declare(strict_types=1);

namespace Modules\Expo\Filament\Resources\Expos;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Expo\Filament\Resources\Expos\Pages\CreateExpo;
use Modules\Expo\Filament\Resources\Expos\Pages\EditExpo;
use Modules\Expo\Filament\Resources\Expos\Pages\ListExpos;
use Modules\Expo\Filament\Resources\Expos\RelationManagers\StationsRelationManager;
use Modules\Expo\Filament\Resources\Expos\Schemas\ExpoForm;
use Modules\Expo\Filament\Resources\Expos\Tables\ExposTable;
use Modules\Expo\Models\Expo;

final class ExpoResource extends Resource
{
    protected static ?string $model = Expo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    public static function form(Schema $schema): Schema
    {
        return ExpoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExposTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpos::route('/'),
            'create' => CreateExpo::route('/create'),
            'edit' => EditExpo::route('/{record}/edit'),
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

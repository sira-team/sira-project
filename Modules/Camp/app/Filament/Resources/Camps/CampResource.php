<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\CampExpensesRelationManager;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\CampUsersRelationManager;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\CampVisitorsRelationManager;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampForm;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampInfolist;
use Modules\Camp\Filament\Resources\Camps\Tables\CampTable;
use Modules\Camp\Models\Camp;

final class CampResource extends Resource
{
    protected static ?string $model = Camp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Camps';

    public static function form(Schema $schema): Schema
    {
        return CampForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CampInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CampExpensesRelationManager::class,
            CampVisitorsRelationManager::class,
            CampUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCamps::route('/'),
            'create' => Pages\CreateCamp::route('/create'),
            'edit' => Pages\EditCamp::route('/{record}/edit'),
            'view' => Pages\ViewCamp::route('/{record}'),
        ];
    }
}

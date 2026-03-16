<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Hostels\RelationManagers\HostelRoomsRelationManager;
use Modules\Camp\Filament\Resources\Hostels\Schemas\HostelForm;
use Modules\Camp\Filament\Resources\Hostels\Tables\HostelTable;
use Modules\Camp\Models\Hostel;

final class HostelResource extends Resource
{
    protected static ?string $model = Hostel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel = 'Hostels';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return HostelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HostelTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            HostelRoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHostels::route('/'),
            'create' => Pages\CreateHostel::route('/create'),
            'view' => Pages\ViewHostel::route('/{record}'),
            'edit' => Pages\EditHostel::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampVisitors;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Filament\Resources\CampVisitors\Pages\ListCampVisitors;
use Modules\Camp\Filament\Resources\CampVisitors\Pages\ViewCampVisitor;
use Modules\Camp\Filament\Resources\CampVisitors\Schemas\CampVisitorInfolist;
use Modules\Camp\Filament\Resources\CampVisitors\Tables\CampVisitorsTable;
use Modules\Camp\Models\CampVisitor;

final class CampVisitorResource extends Resource
{
    protected static ?string $model = CampVisitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $parentResource = CampResource::class;

    protected static bool $isScopedToTenant = false;

    public static function infolist(Schema $schema): Schema
    {
        return CampVisitorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampVisitorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampVisitors::route('/'),
            'view' => ViewCampVisitor::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Visitor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Visitors');
    }
}

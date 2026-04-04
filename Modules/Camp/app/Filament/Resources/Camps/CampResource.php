<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps;

use BackedEnum;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampForm;
use Modules\Camp\Filament\Resources\Camps\Schemas\CampInfolist;
use Modules\Camp\Filament\Resources\Camps\Tables\CampTable;
use Modules\Camp\Filament\Resources\CampUsers\Pages\ListCampUsers;
use Modules\Camp\Filament\Resources\CampVisitors\Pages\ListCampVisitors;
use Modules\Camp\Filament\Resources\Expenses\Pages\ListExpenses;
use Modules\Camp\Models\Camp;

final class CampResource extends Resource
{
    protected static ?string $model = Camp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Camps';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CampForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CampInfolist::configure($schema);
    }

    public static function getRecordTitle(?Model $record): string
    {
        return $record instanceof Camp ? $record->name : __('Camp');
    }

    public static function table(Table $table): Table
    {
        return CampTable::configure($table);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewCamp::class,
            Pages\EditCamp::class,
            ListExpenses::class,
            ListCampVisitors::class,
            ListCampUsers::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [];
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

    public static function getModelLabel(): string
    {
        return __('Camp');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Camps');
    }

    public static function getNavigationLabel(): string
    {
        return __('Camps');
    }
}

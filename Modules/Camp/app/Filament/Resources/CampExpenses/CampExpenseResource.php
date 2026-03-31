<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\CampExpenses;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\CampExpenses\Pages\ListCampExpenses;
use Modules\Camp\Filament\Resources\CampExpenses\Schemas\CampExpenseForm;
use Modules\Camp\Filament\Resources\CampExpenses\Tables\CampExpensesTable;
use Modules\Camp\Filament\Resources\Camps\CampResource;
use Modules\Camp\Models\CampExpense;

final class CampExpenseResource extends Resource
{
    protected static ?string $model = CampExpense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $parentResource = CampResource::class;

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return CampExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampExpensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampExpenses::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Expense');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Expenses');
    }
}

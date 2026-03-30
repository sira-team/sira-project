<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\CampExpenseForm;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\CampExpensesTable;

final class CampExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    public static function getRecordTitleAttribute(): string
    {
        return self::getModelLabel();
    }

    public function form(Schema $schema): Schema
    {
        return CampExpenseForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CampExpensesTable::configure($table);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    protected static function getModelLabel(): string
    {
        return __('Expense');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('Expenses');
    }

    protected static function getRecordLabel(): string
    {
        return self::getModelLabel();
    }

    protected static function getPluralRecordLabel(): string
    {
        return self::getPluralModelLabel();
    }
}

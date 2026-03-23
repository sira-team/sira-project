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
}

<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\ContractForm;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\ContractTable;

final class ContractRelationManager extends RelationManager
{
    protected static string $relationship = 'contract';

    public function form(Schema $schema): Schema
    {
        return ContractForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return ContractTable::configure($table);
    }

    public function canCreate(): bool
    {
        return false;
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

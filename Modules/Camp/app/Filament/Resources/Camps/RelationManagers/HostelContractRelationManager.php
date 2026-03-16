<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\HostelContractForm;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\HostelContractTable;

final class HostelContractRelationManager extends RelationManager
{
    protected static string $relationship = 'hostelContract';

    public function form(Schema $schema): Schema
    {
        return HostelContractForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return HostelContractTable::configure($table);
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

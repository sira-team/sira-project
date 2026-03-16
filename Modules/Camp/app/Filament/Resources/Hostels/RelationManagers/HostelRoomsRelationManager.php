<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Hostels\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Hostels\RelationManagers\Schemas\HostelRoomForm;
use Modules\Camp\Filament\Resources\Hostels\RelationManagers\Tables\HostelRoomsTable;

final class HostelRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Schema $schema): Schema
    {
        return HostelRoomForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return HostelRoomsTable::configure($table);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

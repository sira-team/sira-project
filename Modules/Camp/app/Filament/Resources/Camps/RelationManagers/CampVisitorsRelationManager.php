<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\CampVisitorForm;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\CampRegistrationsTable;

final class CampVisitorsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public function form(Schema $schema): Schema
    {
        return CampVisitorForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CampRegistrationsTable::configure($table);
    }

    public function canCreate(): bool
    {
        return false;
    }
}

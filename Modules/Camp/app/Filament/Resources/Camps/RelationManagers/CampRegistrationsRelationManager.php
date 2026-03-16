<?php

declare(strict_types=1);

namespace Modules\Camp\Filament\Resources\Camps\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Schemas\CampRegistrationForm;
use Modules\Camp\Filament\Resources\Camps\RelationManagers\Tables\CampRegistrationsTable;

final class CampRegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public function form(Schema $schema): Schema
    {
        return CampRegistrationForm::configure($schema);
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

<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Pages\CreateAcademyLevel;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Pages\EditAcademyLevel;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Pages\ListAcademyLevels;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\RelationManagers\SessionsRelationManager;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Schemas\AcademyLevelForm;
use Modules\Academy\Filament\AcademyContent\Resources\AcademyLevels\Tables\AcademyLevelsTable;
use Modules\Academy\Models\AcademyLevel;

final class AcademyLevelResource extends Resource
{
    protected static ?string $model = AcademyLevel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return AcademyLevelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademyLevelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcademyLevels::route('/'),
            'create' => CreateAcademyLevel::route('/create'),
            'edit' => EditAcademyLevel::route('/{record}/edit'),
        ];
    }
}

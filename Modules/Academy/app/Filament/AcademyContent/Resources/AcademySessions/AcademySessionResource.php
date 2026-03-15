<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademySessions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Pages\CreateAcademySession;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Pages\EditAcademySession;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Pages\ListAcademySessions;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\RelationManagers\QuizRelationManager;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Schemas\AcademySessionForm;
use Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\Tables\AcademySessionsTable;
use Modules\Academy\Models\AcademySession;

final class AcademySessionResource extends Resource
{
    protected static ?string $model = AcademySession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return AcademySessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademySessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuizRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcademySessions::route('/'),
            'create' => CreateAcademySession::route('/create'),
            'edit' => EditAcademySession::route('/{record}/edit'),
        ];
    }
}

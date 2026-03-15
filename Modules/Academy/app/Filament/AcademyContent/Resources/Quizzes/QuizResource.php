<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages\EditQuiz;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\RelationManagers\QuestionsRelationManager;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Schemas\QuizForm;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Tables\QuizzesTable;
use Modules\Academy\Models\Quiz;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'title';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}

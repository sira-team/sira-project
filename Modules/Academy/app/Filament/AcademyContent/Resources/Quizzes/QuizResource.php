<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages\CreateQuiz;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages\EditQuiz;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages\ListQuizzes;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Schemas\QuizForm;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Tables\QuizzesTable;
use Modules\Academy\Models\Quiz;

final class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'title';

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\AcademySessions\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\QuizResource;

class QuizRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    protected static ?string $title = 'Quiz';

    public function canCreate(): bool
    {
        return ! $this->getOwnerRecord()->quiz()->exists();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('max_attempts')
                    ->required()
                    ->numeric()
                    ->default(3)
                    ->minValue(1),
                TextInput::make('min_days_between_attempts')
                    ->required()
                    ->numeric()
                    ->default(7)
                    ->minValue(0),
                TextInput::make('passing_score_percent')
                    ->required()
                    ->numeric()
                    ->default(70)
                    ->minValue(1)
                    ->maxValue(100),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('max_attempts')
                    ->numeric(),
                TextColumn::make('passing_score_percent')
                    ->numeric()
                    ->suffix('%'),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record): string => QuizResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

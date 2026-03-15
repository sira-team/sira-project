<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Academy\Enums\QuizQuestionType;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('question_text')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options(collect(QuizQuestionType::cases())->mapWithKeys(
                        fn (QuizQuestionType $type) => [$type->value => $type->label()]
                    ))
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Section::make('Answer Options')
                    ->schema([
                        Repeater::make('options')
                            ->relationship('options')
                            ->schema([
                                TextInput::make('text')
                                    ->required(),
                                Toggle::make('is_correct')
                                    ->label('Correct answer'),
                            ])
                            ->minItems(2)
                            ->maxItems(4)
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                TextColumn::make('question_text')
                    ->limit(60)
                    ->label('Question'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof QuizQuestionType ? $state->label() : $state),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordActions([
                EditAction::make()->slideOver(),
            ])
            ->toolbarActions([
                CreateAction::make()->slideOver(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

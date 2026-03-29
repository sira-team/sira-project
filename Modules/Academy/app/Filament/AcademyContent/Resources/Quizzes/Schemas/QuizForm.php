<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Quiz Settings'))
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('max_attempts')
                                    ->label(__('Max Attempts'))
                                    ->required()
                                    ->numeric()
                                    ->default(3)
                                    ->minValue(1),
                                TextInput::make('min_days_between_attempts')
                                    ->label(__('Min Days Between Attempts'))
                                    ->required()
                                    ->numeric()
                                    ->default(7)
                                    ->minValue(0),
                                TextInput::make('passing_score_percent')
                                    ->label(__('Passing Score (%)'))
                                    ->required()
                                    ->numeric()
                                    ->default(70)
                                    ->minValue(1)
                                    ->maxValue(100),
                            ]),
                    ]),

                Section::make(__('Questions'))
                    ->schema([
                        Builder::make('questions')
                            ->label(__('Quiz'))
                            ->blocks([
                                Block::make('single_choice')
                                    ->label(__('Single Choice'))
                                    ->icon(Heroicon::OutlinedCheckCircle)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label(__('Question'))
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Repeater::make('options')
                                            ->label(__('Answer Options'))
                                            ->schema(self::optionSchema())
                                            ->minItems(2)
                                            ->maxItems(6)
                                            ->required()
                                            ->columns(5)
                                            ->columnSpanFull(),
                                    ]),

                                Block::make('multiple_choice')
                                    ->label(__('Multiple Choice'))
                                    ->icon(Heroicon::OutlinedListBullet)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label(__('Question'))
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Repeater::make('options')
                                            ->label(__('Answer Options'))
                                            ->schema(self::optionSchema())
                                            ->minItems(2)
                                            ->maxItems(6)
                                            ->required()
                                            ->columns(5)
                                            ->columnSpanFull(),
                                    ]),

                                Block::make('true_or_false')
                                    ->label(__('True or False'))
                                    ->icon(Heroicon::OutlinedScale)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label(__('Question'))
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Radio::make('correct_answer')
                                            ->label(__('Correct Answer'))
                                            ->options(['true' => 'True', 'false' => 'False'])
                                            ->default('true')
                                            ->required()
                                            ->inline(),
                                    ]),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @return array<int, mixed>
     */
    private static function optionSchema(): array
    {
        return [
            TextInput::make('text')
                ->label(__('Option'))
                ->required()
                ->columnSpan(3),
            Toggle::make('is_correct')
                ->label(__('Correct'))
                ->default(false)
                ->columnSpan(1),
            TextInput::make('points')
                ->label(__('Pts'))
                ->numeric()
                ->default(1)
                ->minValue(0)
                ->columnSpan(1),
        ];
    }
}

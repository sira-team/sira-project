<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Modules\Academy\Models\AcademyLevel;

final class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quiz Settings')
                    ->schema([
                        Select::make('academy_level_id')
                            ->label('Level')
                            ->options(
                                AcademyLevel::query()
                                    ->orderBy('sort_order')
                                    ->pluck('title', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->disabledOn('edit'),
                        TextInput::make('title')
                            ->required()
                            ->columnSpan(2),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('max_attempts')
                                    ->label('Max Attempts')
                                    ->required()
                                    ->numeric()
                                    ->default(3)
                                    ->minValue(1),
                                TextInput::make('min_days_between_attempts')
                                    ->label('Min Days Between Attempts')
                                    ->required()
                                    ->numeric()
                                    ->default(7)
                                    ->minValue(0),
                                TextInput::make('passing_score_percent')
                                    ->label('Passing Score (%)')
                                    ->required()
                                    ->numeric()
                                    ->default(70)
                                    ->minValue(1)
                                    ->maxValue(100),
                            ]),
                    ]),

                Section::make('Questions')
                    ->schema([
                        Builder::make('questions')
                            ->label('')
                            ->blocks([
                                Block::make('single_choice')
                                    ->label('Single Choice')
                                    ->icon(Heroicon::OutlinedCheckCircle)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label('Question')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Repeater::make('options')
                                            ->label('Answer Options')
                                            ->schema(self::optionSchema())
                                            ->minItems(2)
                                            ->maxItems(6)
                                            ->required()
                                            ->columns(5)
                                            ->columnSpanFull(),
                                    ]),

                                Block::make('multiple_choice')
                                    ->label('Multiple Choice')
                                    ->icon(Heroicon::OutlinedListBullet)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label('Question')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Repeater::make('options')
                                            ->label('Answer Options')
                                            ->schema(self::optionSchema())
                                            ->minItems(2)
                                            ->maxItems(6)
                                            ->required()
                                            ->columns(5)
                                            ->columnSpanFull(),
                                    ]),

                                Block::make('true_or_false')
                                    ->label('True or False')
                                    ->icon(Heroicon::OutlinedScale)
                                    ->schema([
                                        Textarea::make('question_text')
                                            ->label('Question')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Radio::make('correct_answer')
                                            ->label('Correct Answer')
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
                ->label('Option')
                ->required()
                ->columnSpan(3),
            Toggle::make('is_correct')
                ->label('Correct')
                ->default(false)
                ->columnSpan(1),
            TextInput::make('points')
                ->label('Pts')
                ->numeric()
                ->default(1)
                ->minValue(0)
                ->columnSpan(1),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\Academy\Models\AcademySession;

final class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academy_session_id')
                    ->label('Session')
                    ->options(
                        AcademySession::query()
                            ->with('level')
                            ->get()
                            ->mapWithKeys(fn ($session) => [$session->id => "{$session->level->title} — {$session->title}"])
                    )
                    ->required()
                    ->searchable(),
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
}

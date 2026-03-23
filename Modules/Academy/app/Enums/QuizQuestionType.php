<?php

declare(strict_types=1);

namespace Modules\Academy\Enums;

enum QuizQuestionType: string
{
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case TrueOrFalse = 'true_or_false';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Single Choice',
            self::MultipleChoice => 'Multiple Choice',
            self::TrueOrFalse => 'True or False',
        };
    }
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Enums;

enum QuizQuestionType: string
{
    case MultipleChoice = 'multiple_choice';
    case TrueOrFalse = 'true_or_false';

    public function label(): string
    {
        return match ($this) {
            self::MultipleChoice => 'Multiple Choice',
            self::TrueOrFalse => 'True or False',
        };
    }
}

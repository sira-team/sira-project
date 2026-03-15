<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\QuizResource;

final class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
}

<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\QuizResource;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

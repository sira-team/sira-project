<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\QuizResource;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Traits\SyncsQuizQuestions;

/**
 * @property Quiz|null $record
 */
final class CreateQuiz extends CreateRecord
{
    use SyncsQuizQuestions;

    protected static string $resource = QuizResource::class;

    private array $questionsBuilderData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->questionsBuilderData = $data['questions'] ?? [];
        unset($data['questions']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncQuestionsToDatabase($this->record, $this->questionsBuilderData);
    }
}

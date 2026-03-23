<?php

declare(strict_types=1);

namespace Modules\Academy\Filament\AcademyContent\Resources\Quizzes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Academy\Filament\AcademyContent\Resources\Quizzes\QuizResource;
use Modules\Academy\Traits\SyncsQuizQuestions;

final class EditQuiz extends EditRecord
{
    use SyncsQuizQuestions;

    protected static string $resource = QuizResource::class;

    private array $questionsBuilderData = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['questions'] = $this->hydrateQuestionsForBuilder($this->record);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->questionsBuilderData = $data['questions'] ?? [];
        unset($data['questions']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncQuestionsToDatabase($this->record, $this->questionsBuilderData);
    }
}

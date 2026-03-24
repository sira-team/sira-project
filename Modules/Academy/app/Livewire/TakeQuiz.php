<?php

declare(strict_types=1);

namespace Modules\Academy\Livewire;

use Livewire\Component;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizAttempt;
use Modules\Academy\Services\QuizScoreService;

final class TakeQuiz extends Component
{
    public Quiz $quiz;

    public int $currentIndex = 0;

    /** @var array<int, array<int>> keyed by question_id, value is array of selected option_ids */
    public array $answers = [];

    public ?QuizAttempt $attempt = null;

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz->load(['questions.options']);
    }

    public function selectOption(int $questionId, int $optionId): void
    {
        $this->answers[$questionId] = [$optionId];
    }

    public function toggleOption(int $questionId, int $optionId): void
    {
        $current = $this->answers[$questionId] ?? [];

        if (in_array($optionId, $current, true)) {
            $this->answers[$questionId] = array_values(
                array_filter($current, fn ($id) => $id !== $optionId)
            );
        } else {
            $this->answers[$questionId] = [...$current, $optionId];
        }
    }

    public function next(): void
    {
        if ($this->currentIndex < $this->quiz->questions->count() - 1) {
            $this->currentIndex++;
        }
    }

    public function previous(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function submit(QuizScoreService $scorer): void
    {
        foreach ($this->quiz->questions as $question) {
            if (! isset($this->answers[$question->id])) {
                $this->answers[$question->id] = [];
            }
        }

        $this->attempt = $scorer->score($this->quiz, $this->answers);
    }

    public function isSelected(int $questionId, int $optionId): bool
    {
        return in_array($optionId, $this->answers[$questionId] ?? [], true);
    }

    public function isLastQuestion(): bool
    {
        return $this->currentIndex === $this->quiz->questions->count() - 1;
    }

    public function render(): \Illuminate\View\View
    {
        return view('academy::livewire.take-quiz', [
            'question' => $this->quiz->questions[$this->currentIndex],
            'total' => $this->quiz->questions->count(),
        ])->layout('academy::components.layouts.master');
    }
}

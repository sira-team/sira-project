<?php

declare(strict_types=1);

namespace Modules\Academy\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizAttempt;
use Modules\Academy\Models\QuizQuestion;

final class QuizScoreService
{
    /**
     * @param  array<int, array<int>>  $answers  keyed by question_id, value is array of selected option_ids
     */
    public function score(Quiz $quiz, array $answers): QuizAttempt
    {
        /** @var Collection<int, QuizQuestion> $questions */
        $questions = $quiz->questions()->with('options')->get();

        $correctCount = 0;

        foreach ($questions as $question) {
            $correctOptionIds = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->sort()
                ->values()
                ->toArray();

            $selected = collect($answers[$question->id] ?? [])
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->toArray();

            if ($selected === $correctOptionIds) {
                $correctCount++;
            }
        }

        $total = $questions->count();
        $scorePercent = $total > 0 ? (int) round(($correctCount / $total) * 100) : 0;
        $passed = $scorePercent >= $quiz->passing_score_percent;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'completed_at' => now(),
            'score_percent' => $scorePercent,
            'is_passed' => $passed,
        ]);

        foreach ($questions as $question) {
            $attempt->answers()->create([
                'quiz_question_id' => $question->id,
                'selected_options' => $answers[$question->id] ?? [],
            ]);
        }

        return $attempt;
    }
}

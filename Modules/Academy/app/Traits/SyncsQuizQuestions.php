<?php

declare(strict_types=1);

namespace Modules\Academy\Traits;

use Modules\Academy\Enums\QuizQuestionType;
use Modules\Academy\Models\Quiz;

trait SyncsQuizQuestions
{
    /**
     * @param  array<int, array{type: string, data: array<string, mixed>}>  $blocks
     */
    protected function syncQuestionsToDatabase(Quiz $quiz, array $blocks): void
    {
        $quiz->questions()->delete();

        foreach ($blocks as $index => $block) {
            $type = QuizQuestionType::from($block['type']);
            $data = $block['data'];

            $question = $quiz->questions()->create([
                'question_text' => $data['question_text'],
                'type' => $type,
                'sort_order' => $index,
            ]);

            if ($type === QuizQuestionType::TrueOrFalse) {
                $trueIsCorrect = ($data['correct_answer'] ?? 'true') === 'true';
                $question->options()->createMany([
                    ['text' => 'True', 'is_correct' => $trueIsCorrect, 'points' => $trueIsCorrect ? 1.0 : 0.0],
                    ['text' => 'False', 'is_correct' => ! $trueIsCorrect, 'points' => ! $trueIsCorrect ? 1.0 : 0.0],
                ]);
            } else {
                foreach ($data['options'] ?? [] as $optionData) {
                    $question->options()->create([
                        'text' => $optionData['text'],
                        'is_correct' => (bool) ($optionData['is_correct'] ?? false),
                        'points' => (float) ($optionData['points'] ?? 1.0),
                    ]);
                }
            }
        }
    }

    /**
     * @return array<int, array{type: string, data: array<string, mixed>}>
     */
    protected function hydrateQuestionsForBuilder(Quiz $quiz): array
    {
        return $quiz->questions()
            ->with('options')
            ->get()
            ->map(function ($question) {
                if ($question->type === QuizQuestionType::TrueOrFalse) {
                    $correctOption = $question->options->firstWhere('is_correct', true);

                    return [
                        'type' => QuizQuestionType::TrueOrFalse->value,
                        'data' => [
                            'question_text' => $question->question_text,
                            'correct_answer' => ($correctOption?->text === 'True') ? 'true' : 'false',
                        ],
                    ];
                }

                return [
                    'type' => $question->type->value,
                    'data' => [
                        'question_text' => $question->question_text,
                        'options' => $question->options->map(fn ($opt) => [
                            'text' => $opt->text,
                            'is_correct' => $opt->is_correct,
                            'points' => $opt->points,
                        ])->toArray(),
                    ],
                ];
            })
            ->toArray();
    }
}

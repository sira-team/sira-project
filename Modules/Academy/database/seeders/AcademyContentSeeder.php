<?php

declare(strict_types=1);

namespace Modules\Academy\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Academy\Enums\QuizQuestionType;
use Modules\Academy\Models\AcademyLevel;
use Modules\Academy\Models\Quiz;
use Modules\Academy\Models\QuizOption;
use Modules\Academy\Models\QuizQuestion;

final class AcademyContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::curriculum() as $levelData) {
            $level = AcademyLevel::firstOrCreate(
                ['title' => $levelData['title']],
                [
                    'description' => $levelData['description'],
                    'duration_months' => $levelData['duration_months'],
                    'sort_order' => $levelData['sort_order'],
                ]
            );

            foreach ($levelData['sessions'] as $quizData) {
                if ($quizData['quiz'] === null) {
                    continue;
                }

                $quiz = Quiz::create([
                    'title' => $quizData['quiz']['title'],
                    'max_attempts' => 3,
                    'min_days_between_attempts' => 7,
                    'passing_score_percent' => 70,
                ]);

                foreach ($quizData['quiz']['questions'] as $sortOrder => $questionData) {
                    $question = QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => $questionData['text'],
                        'type' => $questionData['type'],
                        'sort_order' => $sortOrder + 1,
                    ]);

                    foreach ($questionData['options'] as $optionData) {
                        QuizOption::create([
                            'quiz_question_id' => $question->id,
                            'text' => $optionData['text'],
                            'is_correct' => $optionData['correct'],
                        ]);
                    }
                }
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function curriculum(): array
    {
        return [
            [
                'title' => 'Level 1 – Makkische Periode',
                'description' => 'Das Leben des Propheten Muhammad ﷺ von der Geburt bis zur Hidschra.',
                'duration_months' => 12,
                'sort_order' => 1,
                'sessions' => [
                    [
                        'title' => 'Geburt und Kindheit des Propheten ﷺ',
                        'description' => 'Die Geburt in Mekka, das Jahr des Elefanten, Aufwachsen als Waise.',
                        'sort_order' => 1,
                        'quiz' => [
                            'title' => 'Quiz: Geburt des Propheten ﷺ',
                            'questions' => [
                                [
                                    'text' => 'In welchem Jahr wurde der Prophet Muhammad ﷺ geboren?',
                                    'type' => QuizQuestionType::MultipleChoice,
                                    'options' => [
                                        ['text' => '570 n. Chr. (Jahr des Elefanten)', 'correct' => true],
                                        ['text' => '610 n. Chr.', 'correct' => false],
                                        ['text' => '622 n. Chr.', 'correct' => false],
                                        ['text' => '632 n. Chr.', 'correct' => false],
                                    ],
                                ],
                                [
                                    'text' => 'Der Prophet ﷺ wurde als Waise geboren, da sein Vater bereits vor seiner Geburt verstarb.',
                                    'type' => QuizQuestionType::TrueOrFalse,
                                    'options' => [
                                        ['text' => 'Wahr', 'correct' => true],
                                        ['text' => 'Falsch', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Die erste Offenbarung – Hira',
                        'description' => 'Die Offenbarung in der Höhle Hira und der Beginn der Prophetschaft.',
                        'sort_order' => 2,
                        'quiz' => [
                            'title' => 'Quiz: Erste Offenbarung',
                            'questions' => [
                                [
                                    'text' => 'Welches war das erste Wort der Offenbarung an den Propheten ﷺ?',
                                    'type' => QuizQuestionType::MultipleChoice,
                                    'options' => [
                                        ['text' => 'Iqra (Lies!)', 'correct' => true],
                                        ['text' => 'Bismillah', 'correct' => false],
                                        ['text' => 'Alhamdulillah', 'correct' => false],
                                        ['text' => 'La ilaha illallah', 'correct' => false],
                                    ],
                                ],
                                [
                                    'text' => 'Khadija RA war die erste Person, die den Islam annahm.',
                                    'type' => QuizQuestionType::TrueOrFalse,
                                    'options' => [
                                        ['text' => 'Wahr', 'correct' => true],
                                        ['text' => 'Falsch', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Verfolgung und Geduld in Mekka',
                        'description' => 'Die Prüfungen der frühen Muslime und der Boykott der Banu Haschim.',
                        'sort_order' => 3,
                        'quiz' => null,
                    ],
                ],
            ],
            [
                'title' => 'Level 2 – Madinesische Periode',
                'description' => 'Von der Hidschra bis zu den Schlachten und der Festigung der islamischen Gemeinschaft.',
                'duration_months' => 12,
                'sort_order' => 2,
                'sessions' => [
                    [
                        'title' => 'Die Hidschra nach Madinah',
                        'description' => 'Gründe, Verlauf und Bedeutung der Auswanderung von Mekka nach Madinah.',
                        'sort_order' => 1,
                        'quiz' => [
                            'title' => 'Quiz: Die Hidschra',
                            'questions' => [
                                [
                                    'text' => 'In welchem Jahr fand die Hidschra statt?',
                                    'type' => QuizQuestionType::MultipleChoice,
                                    'options' => [
                                        ['text' => '622 n. Chr.', 'correct' => true],
                                        ['text' => '610 n. Chr.', 'correct' => false],
                                        ['text' => '630 n. Chr.', 'correct' => false],
                                        ['text' => '632 n. Chr.', 'correct' => false],
                                    ],
                                ],
                                [
                                    'text' => 'Die Hidschra markiert den Beginn des islamischen Kalenders.',
                                    'type' => QuizQuestionType::TrueOrFalse,
                                    'options' => [
                                        ['text' => 'Wahr', 'correct' => true],
                                        ['text' => 'Falsch', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Die Schlacht von Badr',
                        'description' => 'Die erste große Schlacht der Muslime und ihre theologische Bedeutung.',
                        'sort_order' => 2,
                        'quiz' => [
                            'title' => 'Quiz: Schlacht von Badr',
                            'questions' => [
                                [
                                    'text' => 'Wie viele Muslime kämpften in der Schlacht von Badr?',
                                    'type' => QuizQuestionType::MultipleChoice,
                                    'options' => [
                                        ['text' => 'Ca. 313', 'correct' => true],
                                        ['text' => 'Ca. 700', 'correct' => false],
                                        ['text' => 'Ca. 1000', 'correct' => false],
                                        ['text' => 'Ca. 100', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Gesellschaft und Verträge in Madinah',
                        'description' => 'Die Verfassung von Madinah und das Zusammenleben verschiedener Gemeinschaften.',
                        'sort_order' => 3,
                        'quiz' => null,
                    ],
                ],
            ],
            [
                'title' => 'Level 3 – Vollendung und Vermächtnis',
                'description' => 'Die Vollendung der Mission: Mekka-Öffnung, Abschiedspilgerfahrt und das Erbe des Propheten ﷺ.',
                'duration_months' => 12,
                'sort_order' => 3,
                'sessions' => [
                    [
                        'title' => 'Die Öffnung Mekkas',
                        'description' => 'Fathu Mekka im Jahr 630 n. Chr. – ohne Blutvergießen.',
                        'sort_order' => 1,
                        'quiz' => [
                            'title' => 'Quiz: Öffnung Mekkas',
                            'questions' => [
                                [
                                    'text' => 'Wie verhielt sich der Prophet ﷺ gegenüber den Mekkanern nach der Öffnung?',
                                    'type' => QuizQuestionType::MultipleChoice,
                                    'options' => [
                                        ['text' => 'Er vergab ihnen und entließ sie in Freiheit', 'correct' => true],
                                        ['text' => 'Er bestrafte die Anführer der Verfolgung', 'correct' => false],
                                        ['text' => 'Er vertrieb alle aus der Stadt', 'correct' => false],
                                        ['text' => 'Er forderte Kriegsbeute', 'correct' => false],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Die Abschiedspilgerfahrt',
                        'description' => 'Die Hutba (Abschiedsrede) des Propheten ﷺ und ihre Botschaft für die Menschheit.',
                        'sort_order' => 2,
                        'quiz' => null,
                    ],
                    [
                        'title' => 'Das Erbe des Propheten ﷺ',
                        'description' => 'Quran, Sunnah und das Vorbild des Propheten ﷺ als Leitfaden für das Leben.',
                        'sort_order' => 3,
                        'quiz' => null,
                    ],
                ],
            ],
        ];
    }
}

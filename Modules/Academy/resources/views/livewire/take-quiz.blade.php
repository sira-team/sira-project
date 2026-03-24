<div class="min-h-screen py-10 px-4">
    <div class="max-w-xl mx-auto">

        {{-- Results screen --}}
        @if ($attempt)
            <div @class([
                'rounded-xl p-8 text-center shadow-sm border',
                'bg-green-50 border-green-200' => $attempt->passed,
                'bg-red-50 border-red-200' => ! $attempt->passed,
            ])>
                @if ($attempt->passed)
                    <div class="text-5xl mb-3">🎉</div>
                    <h2 class="text-xl font-bold text-green-800">Congratulations, you passed!</h2>
                    <p class="mt-2 text-green-700 text-3xl font-bold">{{ $attempt->score_percent }}%</p>
                @else
                    <div class="text-5xl mb-3">📚</div>
                    <h2 class="text-xl font-bold text-red-800">Not passed this time.</h2>
                    <p class="mt-2 text-red-700">Keep studying and try again.</p>
                @endif
            </div>

        {{-- Quiz screen --}}
        @else
            {{-- Header --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $quiz->title }}</h1>
                    <span class="text-sm text-gray-500">{{ $currentIndex + 1 }} / {{ $total }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div
                        class="bg-blue-500 h-1.5 rounded-full transition-all duration-300"
                        style="width: {{ (($currentIndex + 1) / $total) * 100 }}%"
                    ></div>
                </div>
            </div>

            {{-- Question card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">
                    {{ $question->type->label() }}
                </p>
                <p class="text-base font-medium text-gray-900 mb-5">{{ $question->question_text }}</p>

                <div class="space-y-2">
                    @foreach ($question->options as $option)
                        @php
                            $selected = $this->isSelected($question->id, $option->id);
                            $isSingleOrBool = in_array($question->type, [
                                \Modules\Academy\Enums\QuizQuestionType::SingleChoice,
                                \Modules\Academy\Enums\QuizQuestionType::TrueOrFalse,
                            ]);
                        @endphp

                        <button
                            type="button"
                            wire:click="{{ $isSingleOrBool ? 'selectOption' : 'toggleOption' }}({{ $question->id }}, {{ $option->id }})"
                            @class([
                                'w-full text-left px-4 py-3 rounded-lg border text-sm transition-colors',
                                'border-blue-500 bg-blue-50 text-blue-900 font-medium' => $selected,
                                'border-gray-200 bg-white text-gray-700 hover:bg-gray-50' => ! $selected,
                            ])
                        >
                            <span @class([
                                'inline-flex items-center justify-center w-5 h-5 rounded mr-2 border text-xs font-bold align-middle',
                                'bg-blue-500 border-blue-500 text-white' => $selected,
                                'border-gray-300 text-gray-400' => ! $selected,
                            ])>
                                @if ($isSingleOrBool)
                                    @if ($selected) ● @else ○ @endif
                                @else
                                    @if ($selected) ✓ @else + @endif
                                @endif
                            </span>
                            {{ $option->text }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Navigation --}}
            <div class="flex gap-3">
                @if ($currentIndex > 0)
                    <button
                        type="button"
                        wire:click="previous"
                        class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        ← Previous
                    </button>
                @endif

                @if ($this->isLastQuestion())
                    <button
                        type="button"
                        wire:click="submit"
                        class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors"
                    >
                        Submit Quiz
                    </button>
                @else
                    <button
                        type="button"
                        wire:click="next"
                        class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors"
                    >
                        Next →
                    </button>
                @endif
            </div>
        @endif

    </div>
</div>
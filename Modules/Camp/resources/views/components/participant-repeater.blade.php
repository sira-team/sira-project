{{-- Dynamic participant/child repeater section --}}
<div class="border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-950 mb-4">
        @if(in_array($camp->target_group->value, ['children', 'teenagers']))
            {{ __('Child Information') }}
        @elseif($camp->target_group->value === 'adults')
            {{ __('Your Information') }}
        @else
            <span x-text="targetGroup === 'child' ? '{{ __('Child Information') }}' : '{{ __('Your Information') }}'">
                {{ __('Participant Information') }}
            </span>
        @endif
    </h3>

    <div id="participants" class="space-y-4">
        <template x-for="(participant, index) in participants" :key="index">
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                {{-- Remove button --}}
                <button
                    type="button"
                    @click="removeParticipant(index)"
                    x-show="participants.length > 1"
                    class="absolute top-3 right-3 text-gray-400 hover:text-red-600 transition p-1 hover:bg-red-50 rounded"
                    title="{{ __('Remove') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <input type="hidden" :name="`participants[${index}][index]`" :value="index">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Full Name') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            :name="`participants[${index}][name]`"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            x-model="participant.name"
                        >
                        <template x-if="false">
                            {{-- For Laravel validation error mapping --}}
                            @error('participants.*.name')
                                <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                            @enderror
                        </template>
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Date of Birth') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            :name="`participants[${index}][date_of_birth]`"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            x-model="participant.date_of_birth"
                        >
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Gender') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="radio"
                                    :name="`participants[${index}][gender]`"
                                    value="male"
                                    required
                                    @change="participant.gender = 'male'"
                                    :checked="participant.gender === 'male'"
                                    class="sr-only peer"
                                >
                                <span class="px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-gray-400">
                                    {{ __('Male') }}
                                </span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input
                                    type="radio"
                                    :name="`participants[${index}][gender]`"
                                    value="female"
                                    required
                                    @change="participant.gender = 'female'"
                                    :checked="participant.gender === 'female'"
                                    class="sr-only peer"
                                >
                                <span class="px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-gray-400">
                                    {{ __('Female') }}
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Allergies --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Allergies') }}
                            <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                        </label>
                        <input
                            type="text"
                            :name="`participants[${index}][allergies]`"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            x-model="participant.allergies"
                        >
                    </div>

                    {{-- Medications --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Medications') }}
                            <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                        </label>
                        <input
                            type="text"
                            :name="`participants[${index}][medications]`"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            x-model="participant.medications"
                        >
                    </div>

                    {{-- Wishes --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Wishes') }}
                            <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                        </label>
                        <input
                            type="text"
                            :name="`participants[${index}][wishes]`"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            x-model="participant.wishes"
                        >
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Add Another button (for children registrations) --}}
    <div class="mt-4" x-show="targetGroup === 'child'">
        <button
            type="button"
            @click="addParticipant()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            {{ __('Add Another') }}
        </button>
    </div>
</div>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Camp Registration') }} — {{ $camp->tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6 sm:p-8">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-950 mb-2">{{ $camp->name }}</h1>
                <p class="text-gray-600">{{ $camp->tenant->name }}</p>
            </div>

            {{-- Success message --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Registration closed message --}}
            @if (!$camp->registration_is_open)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-800 font-medium">{{ __('Registration is closed') }}</p>
                </div>
            @else
                <form
                    action="{{ route('camp.register.store.family', [$tenant->slug, $camp]) }}"
                    method="POST"
                    class="space-y-6"
                    x-data="{
                        targetGroup: 'family',
                        participants: {{ Js::from(old('participants', [['index' => 0]])) }},
                        addParticipant() {
                            this.participants.push({ index: this.participants.length });
                        },
                        removeParticipant(index) {
                            if (this.participants.length > 1) {
                                this.participants.splice(index, 1);
                            }
                        }
                    }"
                >
                    @csrf

                    {{-- Your Information (Guardian) --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-950 mb-4">{{ __('Your Information') }}</h3>

                        <div class="space-y-4">
                            {{-- Full Name --}}
                            <div>
                                <label for="visitor_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Full Name') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="visitor_name"
                                    name="visitor[name]"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.name') }}"
                                >
                                @error('visitor.name')
                                    <p class="text-red-600 text-sm mt-1.5 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="visitor_email" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Email') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="visitor_email"
                                    name="visitor[email]"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.email') }}"
                                >
                                @error('visitor.email')
                                    <p class="text-red-600 text-sm mt-1.5 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="visitor_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Phone') }}
                                    <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                                </label>
                                <input
                                    type="tel"
                                    id="visitor_phone"
                                    name="visitor[phone]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.phone') }}"
                                >
                                @error('visitor.phone')
                                    <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="visitor_date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Date of Birth') }}
                                </label>
                                <input
                                    type="date"
                                    id="visitor_date_of_birth"
                                    name="visitor[date_of_birth]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.date_of_birth') }}"
                                >
                                @error('visitor.date_of_birth')
                                    <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <x-camp::toggle-field
                                name="visitor[gender]"
                                :label="__('Gender')"
                                :options="['male' => __('Male'), 'female' => __('Female')]"
                                optional
                            />

                            {{-- Allergies --}}
                            <div>
                                <label for="visitor_allergies" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Allergies') }}
                                    <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                                </label>
                                <input
                                    type="text"
                                    id="visitor_allergies"
                                    name="visitor[allergies]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.allergies') }}"
                                >
                                @error('visitor.allergies')
                                    <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Medications --}}
                            <div>
                                <label for="visitor_medications" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Medications') }}
                                    <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                                </label>
                                <input
                                    type="text"
                                    id="visitor_medications"
                                    name="visitor[medications]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.medications') }}"
                                >
                                @error('visitor.medications')
                                    <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Wishes --}}
                            <div>
                                <label for="visitor_wishes" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Wishes') }}
                                    <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                                </label>
                                <input
                                    type="text"
                                    id="visitor_wishes"
                                    name="visitor[wishes]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    value="{{ old('visitor.wishes') }}"
                                >
                                @error('visitor.wishes')
                                    <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Family Members (optional) --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-950 mb-2">
                            {{ __('Family Members') }}
                            <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">{{ __('Add spouse, children, siblings, or other family members') }}</p>

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

                        {{-- Add Another button --}}
                        <div class="mt-4" x-show="participants.length > 0">
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

                    {{-- Terms --}}
                    <x-camp::terms-section />

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 13a3 3 0 105.119-1H9V7a1 1 0 012 0v5.119A3 3 0 005 13z" />
                        </svg>
                        {{ __('Register') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>

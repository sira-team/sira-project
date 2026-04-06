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
                    action="{{ route('camp.register.store.children', [$tenant->slug, $camp]) }}"
                    method="POST"
                    class="space-y-6"
                    x-data="{
                        targetGroup: 'child',
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

                    {{-- Guardian Information --}}
                    <x-camp::guardian-info :includeGuardianLabel="false" />

                    {{-- Participants Repeater --}}
                    <x-camp::participant-repeater :camp="$camp" />

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

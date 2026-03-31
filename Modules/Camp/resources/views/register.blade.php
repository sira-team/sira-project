<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camp Registration - {{ $camp->tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $camp->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $camp->tenant->name }}</p>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (!$camp->registration_is_open)
                <div class="bg-red-50 border border-red-200 rounded p-4">
                    <p class="text-red-800 font-medium">Registration is closed</p>
                </div>
            @else
                <form action="{{ route('camp.register.store', [$tenant->slug, $camp]) }}" method="POST" class="space-y-6"
                    x-data="{
                        targetGroup: '{{ old('target_group', in_array($camp->target_group->value, ['family', 'children', 'teenagers']) ? 'child' : 'myself') }}',
                        participants: {{ Js::from(old('participants', [['index' => 0]])) }},
                        addParticipant() {
                            this.participants.push({ index: this.participants.length });
                        },
                        removeParticipant(index) {
                            if (this.participants.length > 1) {
                                this.participants.splice(index, 1);
                            }
                        }
                    }">
                    @csrf

                    <!-- Target Group Selection (for family camps) -->
                    @if ($camp->target_group->value === 'family')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Registering for</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="target_group" value="myself" x-model="targetGroup" class="mr-3">
                                    <span>Myself</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="target_group" value="child" x-model="targetGroup" class="mr-3">
                                    <span>My child(ren)</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- Visitor Information (Guardian for children, Self for adults) -->
                    <div class="border-t pt-6">
                        <h3 class="font-semibold mb-4" x-text="targetGroup === 'child' ? 'Your Information (Guardian)' : 'Your Information'">Your Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="visitor_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="visitor_name" name="visitor[name]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('visitor.name') }}">
                                @error('visitor.name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="visitor_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="visitor_email" name="visitor[email]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('visitor.email') }}">
                                @error('visitor.email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="visitor_phone" class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                                <input type="tel" id="visitor_phone" name="visitor[phone]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('visitor.phone') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Participant Information -->
                    <div class="border-t pt-6">
                        <h3 class="font-semibold mb-4">
                            @if (in_array($camp->target_group->value, ['children', 'teenagers']))
                                Child Information
                            @elseif ($camp->target_group->value === 'adults')
                                Your Information
                            @else
                                <span x-text="targetGroup === 'child' ? 'Child Information' : 'Your Information'">Participant Information</span>
                            @endif
                        </h3>
                        <div id="participants" class="space-y-6">
                            <template x-for="(participant, index) in participants" :key="index">
                                <div class="participant-block border rounded p-4 bg-gray-50 relative">
                                    <button type="button" @click="removeParticipant(index)" x-show="participants.length > 1" class="absolute top-2 right-2 text-gray-400 hover:text-red-600" title="Remove">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <input type="hidden" :name="`participants[${index}][index]`" :value="index">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" :name="`participants[${index}][name]`" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.name">
                                            <template x-if="false"> {{-- For Laravel validation error mapping --}}
                                                @error('participants.*.name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                            </template>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                            <input type="date" :name="`participants[${index}][date_of_birth]`" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.date_of_birth">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                                            <select :name="`participants[${index}][gender]`" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.gender">
                                                <option value="">Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Allergies</label>
                                            <input type="text" :name="`participants[${index}][allergies]`" class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.allergies">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Medications</label>
                                            <input type="text" :name="`participants[${index}][medications]`" class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.medications">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Wishes</label>
                                            <input type="text" :name="`participants[${index}][wishes]`" class="mt-1 block w-full rounded border-gray-300 shadow-sm" x-model="participant.wishes">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-4" x-show="targetGroup === 'child'">
                            <button type="button" @click="addParticipant()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Add Another
                            </button>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="border-t pt-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms_accepted" value="1" required class="mt-1 mr-3">
                            <span class="text-sm text-gray-700">I accept the terms and conditions</span>
                        </label>
                        @error('terms_accepted')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                        Register
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>

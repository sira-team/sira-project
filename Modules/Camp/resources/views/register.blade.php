<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camp Registration - {{ $camp->tenant->name }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-2">{{ $camp->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $camp->tenant->name }}</p>

            @if (!$camp->registration_open)
                <div class="bg-red-50 border border-red-200 rounded p-4">
                    <p class="text-red-800 font-medium">Registration is closed</p>
                </div>
            @else
                <form action="{{ route('camp.register.store', [$tenant->slug, $camp]) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Target Group Selection (for mixed camps) -->
                    @if ($camp->target_group->value === 'mixed')
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
                        <h3 class="font-semibold mb-4">Your Information</h3>
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
                            @if ($camp->target_group->value === 'juniors')
                                Child Information
                            @elseif ($camp->target_group->value === 'adults')
                                Your Information
                            @else
                                Participant Information
                            @endif
                        </h3>
                        <div id="participants" class="space-y-6">
                            <div class="participant-block border rounded p-4 bg-gray-50">
                                <input type="hidden" name="participants[0][index]" value="0">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="participants[0][name]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.name') }}">
                                        @error('participants.0.name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                        <input type="date" name="participants[0][date_of_birth]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.date_of_birth') }}">
                                        @error('participants.0.date_of_birth')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select name="participants[0][gender]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                            <option value="">Select</option>
                                            <option value="male" {{ old('participants.0.gender') === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('participants.0.gender') === 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('participants.0.gender')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Allergies</label>
                                        <input type="text" name="participants[0][allergies]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.allergies') }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Medications</label>
                                        <input type="text" name="participants[0][medications]" class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.medications') }}">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Medical Notes</label>
                                        <textarea name="participants[0][medical_notes]" rows="2" class="mt-1 block w-full rounded border-gray-300 shadow-sm" placeholder="Anything else we should know?">{{ old('participants.0.medical_notes') }}</textarea>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                                        <input type="text" name="participants[0][emergency_contact_name]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.emergency_contact_name') }}">
                                        @error('participants.0.emergency_contact_name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                                        <input type="tel" name="participants[0][emergency_contact_phone]" required class="mt-1 block w-full rounded border-gray-300 shadow-sm" value="{{ old('participants.0.emergency_contact_phone') }}">
                                        @error('participants.0.emergency_contact_phone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($camp->target_group->value === 'juniors')
                            <button type="button" onclick="addParticipant()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Add Another Child
                            </button>
                        @endif
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

    <script>
        function addParticipant() {
            const container = document.getElementById('participants');
            const count = container.querySelectorAll('.participant-block').length;
            const template = container.querySelector('.participant-block').cloneNode(true);

            // Update all input names to use the new index
            template.querySelectorAll('input, select, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${count}]`);
                    input.setAttribute('name', newName);
                    input.value = '';
                }
            });

            container.appendChild(template);
        }
    </script>
</body>
</html>

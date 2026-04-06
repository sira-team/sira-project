{{-- Additional information for self-registering adults --}}
<div class="border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-950 mb-4">{{ __('Your Information') }}</h3>

    <div class="space-y-4">
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
        <div>
            <label for="visitor_gender" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('Gender') }}
            </label>
            <select
                id="visitor_gender"
                name="visitor[gender]"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-950 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            >
                <option value="">{{ __('Select') }}</option>
                <option value="male" @selected(old('visitor.gender') === 'male')>{{ __('Male') }}</option>
                <option value="female" @selected(old('visitor.gender') === 'female')>{{ __('Female') }}</option>
            </select>
            @error('visitor.gender')
                <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
            @enderror
        </div>

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

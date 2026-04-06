{{-- Guardian/Contact Information Section --}}
<div class="border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-950 mb-4">
        @if($includeGuardianLabel && isset($targetGroup))
            <span x-text="targetGroup === 'child' ? '{{ __('Your Information (Guardian)') }}' : '{{ __('Your Information') }}'">
                {{ __('Your Information') }}
            </span>
        @else
            {{ __('Your Information') }}
        @endif
    </h3>

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
    </div>
</div>

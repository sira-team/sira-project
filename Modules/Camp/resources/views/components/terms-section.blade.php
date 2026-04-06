{{-- Terms and conditions section --}}
<div class="border-t border-gray-200 pt-6">
    <label class="flex items-start gap-3 cursor-pointer group">
        <input
            type="checkbox"
            name="terms_accepted"
            value="1"
            required
            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition"
        >
        <span class="text-sm text-gray-700 group-hover:text-gray-900 transition">
            {{ __('I accept the terms and conditions') }}
        </span>
    </label>
    @error('terms_accepted')
        <p class="text-red-600 text-sm mt-1.5 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>

{{-- Toggle button field (like Filament) --}}
@props([
    'name',
    'label',
    'options' => ['male' => 'Male', 'female' => 'Female'],
    'required' => false,
    'optional' => false,
])

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @elseif($optional)
            <span class="text-gray-500 text-xs font-normal">{{ __('optional') }}</span>
        @endif
    </label>
    <div class="flex gap-2">
        @foreach($options as $value => $label)
            <label class="flex items-center cursor-pointer group">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    @checked(old($name) === $value)
                    @if($required) required @endif
                    class="sr-only peer"
                >
                <span class="px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-gray-400">
                    {{ $label }}
                </span>
            </label>
        @endforeach
    </div>
    @error($name)
        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
    @enderror
</div>
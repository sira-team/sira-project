{{--
    Variables:
      $index        int|string   — participant index (int or '__IDX__' for JS template)
      $old          array        — old() values for this participant
      $customFields Collection   — all FormTemplateField records for this camp
      $isChildren   bool         — true if children camp, false if family camp
      $isRemovable  bool
--}}

<div class="participant-block border border-gray-200 rounded-xl p-4 bg-white relative space-y-4" data-index="{{ $index }}">

    {{-- Remove button (hidden for first in children mode, optional in family) --}}
    <button type="button" class="remove-participant-btn absolute top-2 right-2 text-gray-400 hover:text-red-600"
            title="{{ __('Remove') }}" style="{{ $isRemovable ? '' : 'display:none' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>

    {{-- Name (required) --}}
    <div>
        <label for="p_{{ $index }}_name" class="block text-sm font-medium text-gray-700">
            {{ __('Full Name') }} <span class="text-red-500">*</span>
        </label>
        <input type="text" id="p_{{ $index }}_name" name="participants[{{ $index }}][name]" required
               class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               value="{{ is_string($old['name'] ?? null) ? $old['name'] : '' }}">
        @error("participants.{$index}.name")
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Gender (required for children, optional for family) --}}
    @if ($isChildren)
        <div>
            <label for="p_{{ $index }}_gender" class="block text-sm font-medium text-gray-700">
                {{ __('Gender') }} <span class="text-red-500">*</span>
            </label>
            <select id="p_{{ $index }}_gender" name="participants[{{ $index }}][gender]" required
                    class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">— {{ __('Select') }} —</option>
                <option value="male" @selected(($old['gender'] ?? null) === 'male')>{{ __('Male') }}</option>
                <option value="female" @selected(($old['gender'] ?? null) === 'female')>{{ __('Female') }}</option>
            </select>
            @error("participants.{$index}.gender")
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone (optional for children) --}}
        <div>
            <label for="p_{{ $index }}_phone" class="block text-sm font-medium text-gray-700">
                {{ __('Phone') }}
            </label>
            <input type="tel" id="p_{{ $index }}_phone" name="participants[{{ $index }}][phone]"
                   class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   value="{{ is_string($old['phone'] ?? null) ? $old['phone'] : '' }}">
            @error("participants.{$index}.phone")
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email (optional for children) --}}
        <div>
            <label for="p_{{ $index }}_email" class="block text-sm font-medium text-gray-700">
                {{ __('Email') }}
            </label>
            <input type="email" id="p_{{ $index }}_email" name="participants[{{ $index }}][email]"
                   class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   value="{{ is_string($old['email'] ?? null) ? $old['email'] : '' }}">
            @error("participants.{$index}.email")
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- Custom form fields (all fields, one per participant) --}}
    @foreach ($customFields as $field)
        @include('camp::partials.template-field', [
            'field'    => $field,
            'nameKey'  => "participants[{$index}][custom_fields][{$field->id}]",
            'idKey'    => "p_{$index}_field_{$field->id}",
            'oldValue' => $old['custom_fields'][$field->id] ?? null,
        ])
    @endforeach
</div>

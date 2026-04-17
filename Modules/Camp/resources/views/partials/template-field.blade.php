{{--
    Variables:
      $field     FormTemplateField
      $nameKey   string  — HTML name attribute, e.g. "custom_fields[3]" or "custom_fields[3][]"
      $idKey     string  — HTML id prefix
      $oldValue  mixed   — old() value for repopulation
--}}
@use(Modules\Camp\Enums\FormFieldType)

@if ($field->type === FormFieldType::Section)
    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-800">{{ $field->label }}</h4>
        @if ($field->help_text)
            <p class="text-sm text-gray-500 mt-1">{{ $field->help_text }}</p>
        @endif
    </div>

@else
    <div>
        @if ($field->type !== FormFieldType::Boolean)
            <label for="{{ $idKey }}" class="block text-sm font-medium text-gray-700">
                {{ $field->label }}
                @if ($field->required) <span class="text-red-500">*</span> @endif
            </label>
        @endif

        @if ($field->help_text)
            <p class="text-xs text-gray-500 mt-0.5 mb-1">{{ $field->help_text }}</p>
        @endif

        @switch ($field->type)

            @case (FormFieldType::Text)
            @case (FormFieldType::Email)
            @case (FormFieldType::Phone)
                <input type="{{ $field->type === FormFieldType::Email ? 'email' : ($field->type === FormFieldType::Phone ? 'tel' : 'text') }}"
                       id="{{ $idKey }}" name="{{ $nameKey }}"
                       @if ($field->required) required @endif
                       value="{{ is_string($oldValue) ? $oldValue : '' }}"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @break

            @case (FormFieldType::Number)
                <input type="number" id="{{ $idKey }}" name="{{ $nameKey }}"
                       @if ($field->required) required @endif
                       value="{{ is_numeric($oldValue) ? $oldValue : '' }}"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @break

            @case (FormFieldType::Textarea)
                <textarea id="{{ $idKey }}" name="{{ $nameKey }}" rows="3"
                          @if ($field->required) required @endif
                          class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ is_string($oldValue) ? $oldValue : '' }}</textarea>
                @break

            @case (FormFieldType::Date)
                <input type="date" id="{{ $idKey }}" name="{{ $nameKey }}"
                       @if ($field->required) required @endif
                       value="{{ is_string($oldValue) ? $oldValue : '' }}"
                       class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @break

            @case (FormFieldType::Select)
                <select id="{{ $idKey }}" name="{{ $nameKey }}"
                        @if ($field->required) required @endif
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">— select —</option>
                    @foreach ($field->options ?? [] as $option)
                        <option value="{{ $option }}" @selected($oldValue === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                @break

            @case (FormFieldType::Radio)
                <div class="mt-1 space-y-1">
                    @foreach ($field->options ?? [] as $option)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="{{ $nameKey }}" value="{{ $option }}"
                                   @if ($field->required) required @endif
                                   @checked($oldValue === $option)>
                            {{ $option }}
                        </label>
                    @endforeach
                </div>
                @break

            @case (FormFieldType::Checkbox)
                {{-- Multiple-choice: name ends with [] --}}
                @php $checkedValues = is_array($oldValue) ? $oldValue : [] @endphp
                <div class="mt-1 space-y-1">
                    @foreach ($field->options ?? [] as $option)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="{{ $nameKey }}[]" value="{{ $option }}"
                                   @checked(in_array($option, $checkedValues, true))>
                            {{ $option }}
                        </label>
                    @endforeach
                </div>
                @break

            @case (FormFieldType::Boolean)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" id="{{ $idKey }}" name="{{ $nameKey }}" value="1"
                           @if ($field->required) required @endif
                           @checked((bool) $oldValue)>
                    {{ $field->label }}
                    @if ($field->required) <span class="text-red-500">*</span> @endif
                </label>
                @break

        @endswitch

        @error($nameKey)
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
@endif

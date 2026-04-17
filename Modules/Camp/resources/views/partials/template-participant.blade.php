{{--
    Variables:
      $index        int|string   — participant index (int or '__IDX__' for JS template)
      $old          array        — old() values for this participant
      $postFields   Collection   — FormTemplateField records after the repeater marker
      $genderPolicy CampGenderPolicy
      $isRemovable  bool
--}}
@use(Modules\Camp\Enums\CampGenderPolicy)

<div class="participant-block border rounded-lg p-4 bg-gray-50 relative space-y-4" data-index="{{ $index }}">

    {{-- Remove button --}}
    <button type="button" class="remove-participant-btn absolute top-2 right-2 text-gray-400 hover:text-red-600"
            title="Remove" style="{{ $isRemovable ? '' : 'display:none' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>

    {{-- Post-repeater template custom fields --}}
    @foreach ($postFields as $field)
        @include('camp::partials.template-field', [
            'field'    => $field,
            'nameKey'  => "participants[{$index}][custom_fields][{$field->id}]",
            'idKey'    => "p_{$index}_field_{$field->id}",
            'oldValue' => $old['custom_fields'][$field->id] ?? null,
        ])
    @endforeach
</div>

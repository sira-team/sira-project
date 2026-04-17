<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camp Registration – {{ $camp->tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6">

        <h1 class="text-2xl font-bold mb-1">{{ $camp->name }}</h1>
        <p class="text-gray-500 mb-6">{{ $camp->tenant->name }}</p>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
                <p class="text-red-800 font-medium mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('camp.register.store', [$tenant->slug, $camp]) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Template fields --}}
            @foreach ($preFields as $field)
                @include('camp::partials.template-field', [
                    'field'    => $field,
                    'nameKey'  => "custom_fields[{$field->id}]",
                    'idKey'    => "custom_field_{$field->id}",
                    'oldValue' => old("custom_fields.{$field->id}"),
                ])
            @endforeach

            @if ($hasRepeater)
                {{-- Participant repeater --}}
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-900 mb-4" id="participants-heading">Participant(s)</h3>

                    <div id="participants-container" class="space-y-6">
                        @php $oldParticipants = old('participants', [[]]) @endphp
                        @foreach ($oldParticipants as $pIndex => $oldParticipant)
                            @include('camp::partials.template-participant', [
                                'index'          => $pIndex,
                                'old'            => $oldParticipant,
                                'postFields'     => $postFields,
                                'genderPolicy'   => $camp->gender_policy,
                                'isRemovable'    => count($oldParticipants) > 1,
                            ])
                        @endforeach
                    </div>

                    <button type="button" id="add-participant-btn"
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                        + Add another person
                    </button>
                </div>
            @endif

            <button type="submit"
                    class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                Register
            </button>
        </form>
    </div>
</div>

@if ($hasRepeater)
{{-- Hidden participant block template for JS cloning --}}
<div id="participant-template" style="display:none" aria-hidden="true">
    @include('camp::partials.template-participant', [
        'index'        => '__IDX__',
        'old'          => [],
        'postFields'   => $postFields,
        'genderPolicy' => $camp->gender_policy,
        'isRemovable'  => true,
    ])
</div>

<script>
(function () {
    'use strict';

    const container  = document.getElementById('participants-container');
    const addBtn     = document.getElementById('add-participant-btn');
    const tpl        = document.getElementById('participant-template');
    let count        = container.querySelectorAll('.participant-block').length;

    function cloneParticipant() {
        const raw  = tpl.innerHTML.trim();
        const html = raw.replace(/__IDX__/g, count);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const block = wrapper.firstElementChild;

        // Reset all field values in the clone
        block.querySelectorAll('input:not([type=hidden]), select, textarea').forEach(function (el) {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });

        container.appendChild(block);
        count++;
        updateRemoveVisibility();
    }

    function removeParticipant(btn) {
        const block = btn.closest('.participant-block');
        if (container.querySelectorAll('.participant-block').length > 1) {
            block.remove();
            updateRemoveVisibility();
        }
    }

    function updateRemoveVisibility() {
        const blocks = container.querySelectorAll('.participant-block');
        const showRemove = blocks.length > 1;
        blocks.forEach(function (block) {
            const btn = block.querySelector('.remove-participant-btn');
            if (btn) {
                btn.style.display = showRemove ? '' : 'none';
            }
        });
    }

    addBtn.addEventListener('click', cloneParticipant);

    // Delegate remove-button clicks
    container.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-participant-btn');
        if (btn) {
            removeParticipant(btn);
        }
    });

    updateRemoveVisibility();
}());
</script>
@endif
</body>
</html>

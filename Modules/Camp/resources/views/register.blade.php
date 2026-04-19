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
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow ring-1 ring-gray-950/5 p-6 sm:p-8">

            {{-- Header --}}
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-950">{{ $camp->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $camp->tenant->name }}</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-red-800 font-medium mb-1">{{ __('Please fix the following errors:') }}</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (!$camp->registration_is_open)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-800 font-medium">{{ __('Registration is closed') }}</p>
                </div>
            @else
                <form action="{{ route('camp.register.store', [$tenant->slug, $camp]) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- HARDCODED: Guardian / Self section --}}
                    <section class="space-y-4">
                        <h3 class="text-base font-semibold text-gray-950">
                            @if ($camp->target_group->value === 'adults')
                                {{ __('Your Information') }}
                            @else
                                {{ __('Guardian Information') }}
                            @endif
                        </h3>

                        <div>
                            <label for="visitor_name" class="block text-sm font-medium text-gray-700">
                                {{ __('Full Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="visitor_name" name="visitor[name]" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('visitor.name') }}">
                            @error('visitor.name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="visitor_email" class="block text-sm font-medium text-gray-700">
                                {{ __('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="visitor_email" name="visitor[email]" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('visitor.email') }}">
                            @error('visitor.email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="visitor_phone" class="block text-sm font-medium text-gray-700">
                                {{ __('Phone') }}
                            </label>
                            <input type="tel" id="visitor_phone" name="visitor[phone]"
                                   class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('visitor.phone') }}">
                            @error('visitor.phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="visitor_gender" class="block text-sm font-medium text-gray-700">
                                {{ __('Gender') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="visitor_gender" name="visitor[gender]" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">— {{ __('Select') }} —</option>
                                <option value="male" @selected(old('visitor.gender') === 'male')>{{ __('Male') }}</option>
                                <option value="female" @selected(old('visitor.gender') === 'female')>{{ __('Female') }}</option>
                            </select>
                            @error('visitor.gender')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </section>

                    {{-- CUSTOM FIELDS for adults and family-self (applied once to the registrant) --}}
                    @if (in_array($camp->target_group->value, ['adults', 'family']) && $customFields->count() > 0)
                        @foreach ($customFields as $field)
                            @include('camp::partials.template-field', [
                                'field'    => $field,
                                'nameKey'  => "custom_fields[{$field->id}]",
                                'idKey'    => "custom_field_{$field->id}",
                                'oldValue' => old("custom_fields.{$field->id}"),
                            ])
                        @endforeach
                    @endif

                    {{-- PARTICIPANT REPEATER (children and family modes) --}}
                    @if ($camp->target_group->value === 'children' || $camp->target_group->value === 'family')
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="text-base font-semibold text-gray-950 mb-4" id="participants-heading">
                                @if ($camp->target_group->value === 'children')
                                    {{ __('Child Information') }}
                                @else
                                    {{ __('Family Members') }}
                                @endif
                            </h3>

                            <div id="participants-container" class="space-y-4">
                                @php
                                    $oldParticipants = old('participants', $camp->target_group->value === 'children' ? [[]] : []);
                                @endphp
                                @foreach ($oldParticipants as $pIndex => $oldParticipant)
                                    @include('camp::partials.template-participant', [
                                        'index'        => $pIndex,
                                        'old'          => $oldParticipant,
                                        'customFields' => $customFields,
                                        'isChildren'   => $camp->target_group->value === 'children',
                                        'isRemovable'  => $camp->target_group->value === 'children' || count($oldParticipants) > 0,
                                    ])
                                @endforeach
                            </div>

                            @if ($camp->target_group->value === 'family')
                                <button type="button" id="add-participant-btn"
                                        class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Add another family member') }}
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- TERMS --}}
                    <div class="border-t border-gray-100 pt-6">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" name="terms_accepted" value="1" required
                                   class="mt-0.5 rounded border-gray-300">
                            <span class="text-sm text-gray-700">{{ __('I accept the terms and conditions') }}</span>
                        </label>
                        @error('terms_accepted')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        {{ __('Register') }}
                    </button>
                </form>

                @if ($camp->target_group->value === 'children' || $camp->target_group->value === 'family')
                    {{-- Hidden participant block template for JS cloning --}}
                    <div id="participant-template" style="display:none" aria-hidden="true">
                        @include('camp::partials.template-participant', [
                            'index'        => '__IDX__',
                            'old'          => [],
                            'customFields' => $customFields,
                            'isChildren'   => $camp->target_group->value === 'children',
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

                        @if ($camp->target_group->value === 'family')
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
                                if (container.querySelectorAll('.participant-block').length > 0) {
                                    block.remove();
                                    updateRemoveVisibility();
                                }
                            }

                            function updateRemoveVisibility() {
                                const blocks = container.querySelectorAll('.participant-block');
                                blocks.forEach(function (block) {
                                    const btn = block.querySelector('.remove-participant-btn');
                                    if (btn) {
                                        btn.style.display = blocks.length > 0 ? '' : 'none';
                                    }
                                });
                            }

                            if (addBtn) {
                                addBtn.addEventListener('click', cloneParticipant);
                            }

                            // Delegate remove-button clicks
                            container.addEventListener('click', function (e) {
                                const btn = e.target.closest('.remove-participant-btn');
                                if (btn) {
                                    removeParticipant(btn);
                                }
                            });

                            updateRemoveVisibility();
                        @else
                            // Children mode: at least 1 participant required
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

                            if (addBtn) {
                                addBtn.addEventListener('click', function () {
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
                                });
                            }

                            // Delegate remove-button clicks
                            container.addEventListener('click', function (e) {
                                const btn = e.target.closest('.remove-participant-btn');
                                if (btn) {
                                    removeParticipant(btn);
                                }
                            });

                            updateRemoveVisibility();
                        @endif
                    }());
                    </script>
                @endif
            @endif
        </div>
    </div>
</body>
</html>

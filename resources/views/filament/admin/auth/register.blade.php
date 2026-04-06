<x-filament-panels::page.simple>
    <x-slot name="heading">
        {{ $this->getHeading() }}
    </x-slot>

    {{ $this->form }}

    <div class="mt-6">
        <x-filament-socialite::buttons :show-divider="true" />
    </div>
</x-filament-panels::page.simple>
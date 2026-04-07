<x-filament-panels::page.simple>
    <x-slot name="heading">
        {{ $this->getHeading() }}
    </x-slot>

    <form wire:submit="register" class="space-y-6">
        {{ $this->form }}
        <br>
        <x-filament::button type="submit" class="w-full">
            {{ __('auth.register') }}
        </x-filament::button>
    </form>

    <div class="mt-6">
        <x-filament-socialite::buttons :show-divider="true" divider-label="{{ __('auth.or') }}" />
    </div>
</x-filament-panels::page.simple>

@extends('camp::layouts.app')

@php
    $blocks      = $camp->content ?? [];
    $registerUrl = route('camp.register.show', [$tenant->slug, $camp]);
    $imageUrl    = fn (?string $path): ?string => $path
        ? URL::signedRoute('camp.content-image', [$tenant->slug, $camp, 'path' => $path])
        : null;
@endphp

@section('title', $camp->name . ' – ' . $camp->tenant->name)
@section('tenant-name', $camp->tenant->name)

@section('header')
    @php $heroBlock = collect($blocks)->first(fn ($b) => $b['type'] === 'hero'); @endphp

    @if ($heroBlock)
        @php $data = $heroBlock['data'] ?? []; @endphp
        <header class="relative w-full min-h-[60vh] bg-gray-950 text-white overflow-hidden flex items-center">
            @if (!empty($data['image']))
                <img src="{{ $imageUrl($data['image']) }}"
                     alt=""
                     class="absolute inset-0 w-full h-full object-cover opacity-40 pointer-events-none select-none">
            @endif
            <div class="relative w-full">
                <div class="max-w-5xl mx-auto px-6 py-24">
                    <p class="text-xs font-semibold text-gray-300 uppercase tracking-widest mb-4">{{ $camp->tenant->name }}</p>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                        {{ $camp->name }}
                    </h1>
                    @if (!empty($data['tagline']))
                        <p class="mt-6 text-xl text-gray-200 max-w-xl">{{ $data['tagline'] }}</p>
                    @endif
                    @if ($camp->starts_at && $camp->ends_at)
                        <p class="mt-5 text-sm text-gray-400 tracking-wide">
                            {{ $camp->starts_at->isoFormat('D. MMMM') }} – {{ $camp->ends_at->isoFormat('D. MMMM YYYY') }}
                        </p>
                    @endif
                </div>
            </div>
        </header>
    @else
        <header class="w-full bg-gray-950 text-white">
            <div class="max-w-5xl mx-auto px-6 py-20">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">{{ $camp->tenant->name }}</p>
                <h1 class="text-4xl sm:text-5xl font-bold leading-tight">{{ $camp->name }}</h1>
                @if ($camp->starts_at && $camp->ends_at)
                    <p class="mt-5 text-gray-300 text-lg">
                        {{ $camp->starts_at->isoFormat('D. MMMM') }} – {{ $camp->ends_at->isoFormat('D. MMMM YYYY') }}
                    </p>
                @endif
            </div>
        </header>
    @endif
@endsection

@section('content')
    @foreach ($blocks as $block)
        @php $data = $block['data'] ?? []; @endphp

        @if ($block['type'] === 'hero')
            {{-- Rendered in @section('header') --}}

        @elseif ($block['type'] === 'paragraph')
            <section class="w-full py-14">
                <div class="max-w-5xl mx-auto px-6">
                    @if (!empty($data['heading']))
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-950 mb-5">{{ $data['heading'] }}</h2>
                    @endif
                    @if (!empty($data['body']))
                        <div class="prose prose-gray prose-lg max-w-none">{!! $data['body'] !!}</div>
                    @endif
                </div>
            </section>

        @elseif ($block['type'] === 'image')
            <section class="w-full py-8">
                @if (!empty($data['url']))
                    <div class="max-w-5xl mx-auto px-6">
                        <figure>
                            <img src="{{ $imageUrl($data['url']) }}"
                                 alt="{{ $data['alt'] ?? '' }}"
                                 class="w-full rounded-2xl object-cover max-h-[60vh] shadow-sm">
                            @if (!empty($data['caption']))
                                <figcaption class="mt-3 text-sm text-gray-500">{{ $data['caption'] }}</figcaption>
                            @endif
                        </figure>
                    </div>
                @endif
            </section>

        @elseif ($block['type'] === 'highlights')
            <section class="w-full bg-gray-50 py-16">
                <div class="max-w-5xl mx-auto px-6">
                    @if (!empty($data['heading']))
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-950 mb-10">{{ $data['heading'] }}</h2>
                    @endif
                    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($data['items'] ?? [] as $item)
                            <li class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 flex gap-4">
                                <span class="shrink-0 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                    <x-dynamic-component
                                        :component="$item['icon']"
                                        class="w-5 h-5 text-green-600"
                                    />
                                </span>
                                <div>
                                    @if (!empty($item['title']))
                                        <p class="font-semibold text-gray-900">{{ $item['title'] }}</p>
                                    @endif
                                    @if (!empty($item['description']))
                                        <p class="mt-1 text-sm text-gray-500">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

        @elseif ($block['type'] === 'info_box')
            <section class="w-full py-14">
                <div class="max-w-5xl mx-auto px-6">
                    @if (!empty($data['heading']))
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-950 mb-6">{{ $data['heading'] }}</h2>
                    @endif
                    <dl class="divide-y divide-gray-100 rounded-2xl ring-1 ring-gray-200 overflow-hidden">
                        @foreach ($data['items'] ?? [] as $item)
                            <div class="flex gap-6 px-6 py-4 bg-white even:bg-gray-50">
                                <dt class="w-40 shrink-0 text-sm font-semibold text-gray-500">{{ $item['label'] ?? '' }}</dt>
                                <dd class="text-sm text-gray-900">{{ $item['value'] ?? '' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </section>

        @elseif ($block['type'] === 'quote')
            <section class="w-full bg-green-50 py-20">
                <div class="max-w-5xl mx-auto px-6">
                    <svg class="w-10 h-10 text-green-300 mb-6" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                        <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/>
                    </svg>
                    <blockquote class="text-2xl sm:text-3xl font-medium text-gray-800 leading-snug max-w-3xl">
                        {{ $data['text'] ?? '' }}
                    </blockquote>
                    @if (!empty($data['author']))
                        <div class="mt-8">
                            <p class="font-semibold text-gray-900">{{ $data['author'] }}</p>
                            @if (!empty($data['role']))
                                <p class="text-sm text-gray-500 mt-1">{{ $data['role'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </section>

        @elseif ($block['type'] === 'schedule')
            <section class="w-full py-14">
                <div class="max-w-5xl mx-auto px-6">
                    @if (!empty($data['heading']))
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-950 mb-10">{{ $data['heading'] }}</h2>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach ($data['days'] ?? [] as $day)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-950 uppercase tracking-widest mb-4 pb-2 border-b border-gray-200">
                                    {{ $day['day'] ?? '' }}
                                </h3>
                                <ul class="space-y-3">
                                    @foreach ($day['slots'] ?? [] as $slot)
                                        <li class="flex gap-4 text-sm">
                                            @if (!empty($slot['time']))
                                                <span class="w-14 shrink-0 font-mono text-gray-400 pt-px">{{ $slot['time'] }}</span>
                                            @endif
                                            <span class="text-gray-700">{{ $slot['activity'] ?? '' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        @elseif ($block['type'] === 'cta')
            <section class="w-full bg-gray-950 text-white py-20">
                <div class="max-w-5xl mx-auto px-6">
                    @if (!empty($data['headline']))
                        <h2 class="text-3xl sm:text-4xl font-bold">{{ $data['headline'] }}</h2>
                    @endif
                    @if (!empty($data['subtext']))
                        <p class="mt-4 text-gray-300 text-lg max-w-xl">{{ $data['subtext'] }}</p>
                    @endif
                    @if (!empty($data['show_deadline']) && $camp->registration_ends_at)
                        <p class="mt-3 text-sm text-gray-400">
                            {{ __('Registration deadline') }}: {{ $camp->registration_ends_at->isoFormat('D. MMMM YYYY') }}
                        </p>
                    @endif
                    <a href="{{ $registerUrl }}"
                       class="mt-8 inline-block px-8 py-3.5 bg-green-500 hover:bg-green-400 text-white font-semibold rounded-xl text-base transition-colors">
                        {{ $data['button_label'] ?? __('Register Now') }}
                    </a>
                </div>
            </section>

        @endif
    @endforeach

    {{-- Register bar --}}
    @if ($camp->registration_is_open)
        <div class="w-full border-t border-gray-100 bg-white py-6">
            <div class="max-w-5xl mx-auto px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-gray-950 text-lg">{{ $camp->name }}</p>
                    @if ($camp->price_per_participant > 0)
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ __('Price') }}: {{ number_format((float) $camp->price_per_participant, 2, ',', '.') }} €
                        </p>
                    @endif
                </div>
                <a href="{{ $registerUrl }}"
                   class="shrink-0 px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl text-base transition-colors">
                    {{ __('Register Now') }}
                </a>
            </div>
        </div>
    @else
        <div class="w-full border-t border-gray-100 bg-gray-50 py-8">
            <div class="max-w-5xl mx-auto px-6 text-sm text-gray-500">
                {{ __('Registration is currently closed.') }}
            </div>
        </div>
    @endif
@endsection

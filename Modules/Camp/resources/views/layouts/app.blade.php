<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', '')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-white text-gray-900 antialiased">

    @hasSection('header')
        @yield('header')
    @else
        <header class="bg-gray-950 text-white py-4">
            <div class="max-w-5xl mx-auto px-6 flex items-center justify-between">
                <span class="font-semibold text-sm tracking-wide">@yield('tenant-name')</span>
            </div>
        </header>
    @endif

    <main class="w-full">
        @yield('content')
    </main>

    <footer class="border-t border-gray-100 py-8">
        <div class="max-w-5xl mx-auto px-6 text-center text-xs text-gray-400">
            @yield('footer', date('Y') . ' ' . config('app.name'))
        </div>
    </footer>

    @stack('scripts')

</body>
</html>

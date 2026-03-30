<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expo Request - {{ $tenant->name }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header with tenant branding -->
        <div class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-2xl mx-auto px-4 py-6 sm:px-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">Expo-Anfrage</p>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 max-w-2xl w-full mx-auto px-4 py-12 sm:px-6">
            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('expo.request.store', $tenant) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Contact Information Section -->
                <div class="bg-white rounded-lg shadow px-6 py-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Kontaktinformationen</h2>

                    <div class="space-y-4">
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700">
                                Kontaktperson *
                            </label>
                            <input
                                type="text"
                                id="contact_name"
                                name="contact_name"
                                value="{{ old('contact_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('contact_name') border-red-300 @else border @enderror"
                                required
                            >
                            @error('contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="organisation_name" class="block text-sm font-medium text-gray-700">
                                Organisationsname *
                            </label>
                            <input
                                type="text"
                                id="organisation_name"
                                name="organisation_name"
                                value="{{ old('organisation_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('organisation_name') border-red-300 @else border @enderror"
                                required
                            >
                            @error('organisation_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                E-Mail-Adresse *
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-300 @else border @enderror"
                                required
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Telefonnummer
                            </label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">
                                Stadt
                            </label>
                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ old('city') }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>
                    </div>
                </div>

                <!-- Event Details Section -->
                <div class="bg-white rounded-lg shadow px-6 py-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Veranstaltungsdetails</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="preferred_date_from" class="block text-sm font-medium text-gray-700">
                                    Bevorzugtes Datum von
                                </label>
                                <input
                                    type="date"
                                    id="preferred_date_from"
                                    name="preferred_date_from"
                                    value="{{ old('preferred_date_from') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                            <div>
                                <label for="preferred_date_to" class="block text-sm font-medium text-gray-700">
                                    Bevorzugtes Datum bis
                                </label>
                                <input
                                    type="date"
                                    id="preferred_date_to"
                                    name="preferred_date_to"
                                    value="{{ old('preferred_date_to') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">
                                Nachricht / Zusätzliche Informationen
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Anfrage absenden
                    </button>
                </div>

                <p class="text-center text-xs text-gray-500 mt-8">
                    * Erforderliche Felder
                </p>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-2xl mx-auto px-4 py-6 sm:px-6">
                <p class="text-sm text-gray-600">
                    Kontakt: {{ $tenant->email ?? 'kontakt@beispiel.de' }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>

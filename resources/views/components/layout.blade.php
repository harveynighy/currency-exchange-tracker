<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Currency Exchange Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen font-['Inter'] antialiased">
    <header class="w-full bg-slate-800 text-white shadow-lg border-b-4 border-blue-600">
        <div class="max-w-6xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Currency Exchange Tracker</h1>
                    <p class="text-sm text-slate-300 mt-1">Professional currency conversion service</p>
                </div>
                <div class="hidden md:flex items-center gap-4 text-xs text-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                        Live Rates
                    </span>
                    <span class="text-slate-400">|</span>
                    <span>Powered by ExchangeRate API</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 mt-auto">
        <div class="max-w-6xl mx-auto px-6 py-6">
            <p class="text-center text-xs text-gray-500">
                © {{ date('Y') }} Currency Exchange Tracker. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>

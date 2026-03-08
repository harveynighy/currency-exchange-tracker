<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'FX Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen font-['Inter'] antialiased">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-8 py-5">
            <div class="flex items-center gap-4">
                <div>
                    <a href="/" class="inline-block">
                        <img src="{{ asset('fx-tracker-logo.png') }}" alt="FX Tracker" class="h-8 w-auto">
                    </a>
                    <p class="text-xs text-slate-500">A sub-division of Infinite Finances</p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm text-slate-700">
                <span class="status-pill hidden sm:inline-flex">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Live Rates
                </span>
                @auth
                    <a href="/profile" class="secondary-btn px-4 py-2 text-sm">Profile</a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="primary-btn px-4 py-2 text-sm">Logout</button>
                    </form>
                @else
                    <a href="/login" class="secondary-btn px-4 py-2 text-sm">Login</a>
                    <a href="/register" class="primary-btn px-4 py-2 text-sm">Create account</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-8 py-14">
        {{ $slot }}
    </main>

    <footer class="mt-auto border-t border-slate-200">
        <div class="mx-auto max-w-7xl px-8 py-8">
            <div class="mb-6 space-y-4">
                <p class="text-center text-xs text-slate-500">
                    © {{ date('Y') }} FX Tracker · A sub-division of Infinite Finances.
                </p>
                <div class="flex flex-wrap justify-center gap-6 text-center text-xs text-slate-600">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-blue-600">Privacy Policy</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('cookie-policy') }}" class="hover:text-blue-600">Cookie Policy</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('terms-of-service') }}" class="hover:text-blue-600">Terms of Service</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('refund-policy') }}" class="hover:text-blue-600">Refund Policy</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('acceptable-use-policy') }}" class="hover:text-blue-600">Acceptable Use</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('data-processing-agreement') }}" class="hover:text-blue-600">DPA</a>
                    <span class="text-slate-300">·</span>
                    <a href="{{ route('api-terms') }}" class="hover:text-blue-600">API Terms</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>

<!DOCTYPE html>
@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'robots' => 'index, follow',
    'canonical' => null,
])

@php
    $appName = config('app.name', 'FX Tracker');
    $metaTitle = $title ?: "Currency Rate Tracker & Exchange Calculator | {$appName}";
    $metaDescription = $description
        ?: 'Track live and historical currency exchange rates, compare trends across global currencies, and convert amounts instantly with a fast currency rate tracker.';
    $metaKeywords = $keywords
        ?: 'currency rate tracker, currency exchange, exchange rate tracker, currency converter, live exchange rates, historical exchange rates, forex rates, FX rates';
    $canonicalUrl = $canonical ?: url()->current();
    $siteUrl = rtrim(config('app.url', url('/')), '/');
    $metaImage = $siteUrl . '/fx-tracker-logo.png';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="author" content="{{ $appName }}">

    <link rel="canonical" href="{{ $canonicalUrl }}">

    <title>{{ $metaTitle }}</title>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('fx-tracker-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('fx-tracker-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $appName,
            'url' => $siteUrl,
            'description' => $metaDescription,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $siteUrl . '/charts',
                'query-input' => 'required name=currency pair',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>

<body class="min-h-screen font-['Inter'] antialiased">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-8 sm:py-5">
            <div class="flex items-center gap-4">
                <div>
                    <a href="/" class="inline-block">
                        <img src="{{ asset('fx-tracker-logo.png') }}" alt="FX Tracker" class="h-8 w-auto max-w-[50vw] object-contain sm:max-w-none">
                    </a>
                    <p class="text-xs text-slate-500">A sub-division of Infinite Finances</p>
                </div>
            </div>

            <div class="hidden items-center gap-3 text-sm text-slate-700 md:flex">
                <a href="/charts" class="rounded-lg px-3 py-2 font-semibold text-blue-600 transition hover:bg-blue-50">Historical Charts</a>
                <a href="/blog" class="rounded-lg px-3 py-2 font-medium transition hover:bg-slate-100">Blog</a>
                <a href="/api-docs" class="rounded-lg px-3 py-2 font-medium transition hover:bg-slate-100">API Docs</a>
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

            <button id="mobile-menu-toggle" type="button"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 md:hidden"
                aria-controls="mobile-menu" aria-expanded="false" aria-label="Toggle menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden border-t border-slate-200 px-4 py-3 md:hidden">
            <div class="flex flex-col gap-2">
                <a href="/charts" class="rounded-lg bg-blue-50 px-3 py-2 text-center font-semibold text-blue-600 transition hover:bg-blue-100">Historical Charts</a>
                <a href="/blog" class="rounded-lg px-3 py-2 text-center font-medium transition hover:bg-slate-100">Blog</a>
                <a href="/faq" class="rounded-lg px-3 py-2 text-center font-medium transition hover:bg-slate-100">FAQ</a>
                <a href="/api-docs" class="rounded-lg px-3 py-2 text-center font-medium transition hover:bg-slate-100">API Docs</a>
                <span class="status-pill inline-flex w-fit">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Live Rates
                </span>
                @auth
                    <a href="/profile" class="secondary-btn w-full px-3 py-2 text-center text-sm">Profile</a>
                    <form method="POST" action="/logout" class="w-full">
                        @csrf
                        <button type="submit" class="primary-btn w-full px-3 py-2 text-sm">Logout</button>
                    </form>
                @else
                    <a href="/login" class="secondary-btn w-full px-3 py-2 text-center text-sm">Login</a>
                    <a href="/register" class="primary-btn w-full px-3 py-2 text-center text-sm">Create account</a>
                @endauth
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');

            if (!toggle || !menu) return;

            toggle.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
            });
        });
    </script>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8 sm:py-14">
        {{ $slot }}
    </main>

    <footer class="mt-auto border-t border-slate-200">
        <div class="mx-auto max-w-7xl px-8 py-8">
            <div class="mb-6 space-y-4">
                <p class="text-center text-xs text-slate-500">
                    © {{ date('Y') }} FX Tracker · A sub-division of Infinite Finances.
                </p>
                <div class="flex flex-wrap justify-center gap-6 text-center text-xs text-slate-600">
                    <a href="{{ route('charts.index') }}" class="hover:text-blue-600">Charts</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-blue-600">Blog</a>
                    <a href="{{ route('faq') }}" class="hover:text-blue-600">FAQ</a>
                    <a href="{{ route('api-docs') }}" class="hover:text-blue-600">API Docs</a>
                    <a href="{{ route('money.index') }}" class="hover:text-blue-600">Exchange Pages</a>
                </div>

                <details class="mx-auto max-w-3xl rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                    <summary class="cursor-pointer font-semibold text-slate-700">Legal & Policies</summary>
                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2">
                        <a href="{{ route('privacy-policy') }}" class="hover:text-blue-600">Privacy Policy</a>
                        <a href="{{ route('cookie-policy') }}" class="hover:text-blue-600">Cookie Policy</a>
                        <a href="{{ route('terms-of-service') }}" class="hover:text-blue-600">Terms of Service</a>
                        <a href="{{ route('refund-policy') }}" class="hover:text-blue-600">Refund Policy</a>
                        <a href="{{ route('acceptable-use-policy') }}" class="hover:text-blue-600">Acceptable Use</a>
                        <a href="{{ route('data-processing-agreement') }}" class="hover:text-blue-600">DPA</a>
                        <a href="{{ route('api-terms') }}" class="hover:text-blue-600">API Terms</a>
                    </div>
                </details>
            </div>
        </div>
    </footer>
</body>

</html>

<x-layout
    title="Top Currency Exchange Rate Pages | FX Tracker"
    description="Browse high-interest currency exchange pairs with dedicated pages for live conversion and historical trend analysis."
    keywords="currency exchange rates, usd to eur, gbp to usd, eur to usd, fx pairs"
>
    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mb-3 inline-flex rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Popular Pairs</p>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Top Currency Exchange Pages</h1>
                <p class="mt-4 text-lg text-slate-600">Open dedicated pages for major currency pairs and jump straight to conversion and charts.</p>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($pairs as $pair)
                @php
                    $fromName = $currencies[$pair['from']] ?? $pair['from'];
                    $toName = $currencies[$pair['to']] ?? $pair['to'];
                @endphp
                <a href="{{ route('money.show', ['from' => $pair['from'], 'to' => $pair['to']]) }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Exchange Pair</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $pair['from'] }} to {{ $pair['to'] }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ $fromName }} to {{ $toName }}</p>
                    <p class="mt-4 text-sm font-medium text-blue-600">Open page →</p>
                </a>
            @endforeach
        </section>
    </div>
</x-layout>

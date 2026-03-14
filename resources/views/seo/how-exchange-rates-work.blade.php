<x-layout
    title="How Currency Exchange Rates Work | FX Tracker"
    description="Learn how currency exchange rates work, what moves forex prices, and how to read currency pairs for smarter conversion and trend analysis."
    keywords="how exchange rates work, currency exchange explained, forex basics, what moves exchange rates, currency pair guide"
>
    @php
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => 'How Currency Exchange Rates Work',
            'description' => 'A practical guide to understanding currency exchange rates, forex pairs, and common market drivers.',
            'author' => [
                '@type' => 'Organization',
                'name' => 'FX Tracker',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'FX Tracker',
            ],
            'datePublished' => now()->toDateString(),
            'dateModified' => now()->toDateString(),
        ];
    @endphp

    <article class="mx-auto max-w-4xl space-y-8">
        <header class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12">
            <p class="mb-3 inline-flex rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Currency Education</p>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">How Currency Exchange Rates Work</h1>
            <p class="mt-4 text-lg text-slate-600">A clear overview of what exchange rates mean, why they move, and how to use that context with a currency rate tracker.</p>
        </header>

        <section class="space-y-5 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-semibold text-slate-900">1) What is an exchange rate?</h2>
            <p class="text-slate-700">An exchange rate is the value of one currency compared with another. If EUR/USD is 1.10, one euro equals 1.10 US dollars.</p>

            <h2 class="text-2xl font-semibold text-slate-900">2) What is a currency pair?</h2>
            <p class="text-slate-700">Pairs are quoted as <strong>base/quote</strong>. In GBP/USD, GBP is the base currency and USD is the quote currency.</p>

            <h2 class="text-2xl font-semibold text-slate-900">3) Why do exchange rates change?</h2>
            <ul class="list-disc space-y-2 pl-6 text-slate-700">
                <li>Interest rate expectations and central bank policy</li>
                <li>Inflation and economic growth data</li>
                <li>Trade balances and capital flows</li>
                <li>Political events and market risk sentiment</li>
            </ul>

            <h2 class="text-2xl font-semibold text-slate-900">4) Spot vs historical rates</h2>
            <p class="text-slate-700">Spot rates reflect current pricing, while historical rates show how a pair moved over time. Combining both helps with context and timing.</p>

            <h2 class="text-2xl font-semibold text-slate-900">5) How to use FX Tracker effectively</h2>
            <ul class="list-disc space-y-2 pl-6 text-slate-700">
                <li>Use the converter for quick live currency exchange checks</li>
                <li>Use charts to compare trends across periods</li>
                <li>Use the API for integrations and reporting workflows</li>
            </ul>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-2xl font-semibold text-slate-900">Next steps</h2>
            <p class="mt-2 text-slate-600">Explore market movement with charts or review key FX terms in the glossary.</p>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <a href="{{ route('charts.index') }}" class="primary-btn">Open Historical Charts</a>
                <a href="{{ route('seo.currency-glossary') }}" class="secondary-btn">Read Currency Glossary</a>
            </div>
        </section>
    </article>

    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-layout>

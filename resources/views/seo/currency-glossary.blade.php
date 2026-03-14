<x-layout
    title="Currency Exchange Glossary | FX Terms Explained | FX Tracker"
    description="A beginner-friendly glossary of currency exchange and forex terms, including base currency, quote currency, pip, spread, volatility, and more."
    keywords="currency glossary, forex glossary, exchange rate terms, FX terms explained, pip spread volatility"
>
    @php
        $terms = [
            ['term' => 'Base Currency', 'def' => 'The first currency in a pair. In EUR/USD, EUR is the base currency.'],
            ['term' => 'Quote Currency', 'def' => 'The second currency in a pair. In EUR/USD, USD is the quote currency.'],
            ['term' => 'Exchange Rate', 'def' => 'The price of one currency expressed in another currency.'],
            ['term' => 'Pip', 'def' => 'A common measure of movement in a currency pair, typically the fourth decimal place.'],
            ['term' => 'Spread', 'def' => 'The difference between buy and sell prices for a currency pair.'],
            ['term' => 'Volatility', 'def' => 'How much and how quickly exchange rates move over time.'],
            ['term' => 'Cross Rate', 'def' => 'An exchange rate between two non-USD currencies, often derived from USD pairs.'],
            ['term' => 'Spot Rate', 'def' => 'The current market exchange rate for immediate conversion.'],
            ['term' => 'Historical Rate', 'def' => 'A past exchange rate used for trend analysis and reporting.'],
            ['term' => 'Liquidity', 'def' => 'How easily a currency can be bought or sold without large price impact.'],
        ];

        $itemListSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'DefinedTermSet',
            'name' => 'Currency Exchange Glossary',
            'hasDefinedTerm' => array_map(fn($item) => [
                '@type' => 'DefinedTerm',
                'name' => $item['term'],
                'description' => $item['def'],
            ], $terms),
        ];
    @endphp

    <div class="mx-auto max-w-5xl space-y-8">
        <header class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12">
            <p class="mb-3 inline-flex rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Reference</p>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Currency Exchange Glossary</h1>
            <p class="mt-4 text-lg text-slate-600">Simple definitions for common forex and currency exchange terms.</p>
        </header>

        <section class="grid gap-4 sm:grid-cols-2">
            @foreach ($terms as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $item['term'] }}</h2>
                    <p class="mt-2 text-slate-600">{{ $item['def'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <h2 class="text-2xl font-semibold text-slate-900">Use these terms in context</h2>
            <p class="mt-2 text-slate-600">Try a live conversion or inspect long-term movement for your chosen currency pair.</p>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="primary-btn">Open Converter</a>
                <a href="{{ route('charts.index') }}" class="secondary-btn">View Charts</a>
                <a href="{{ route('seo.how-exchange-rates-work') }}" class="secondary-btn">Read Exchange Rate Guide</a>
            </div>
        </section>
    </div>

    <script type="application/ld+json">
        {!! json_encode($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-layout>

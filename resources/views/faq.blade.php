<x-layout
    title="Currency Rate Tracker FAQ | FX Tracker"
    description="Frequently asked questions about currency exchange rates, historical FX charts, API access, and using FX Tracker for live currency conversion."
    keywords="currency rate tracker FAQ, exchange rate FAQ, currency converter questions, FX API questions, historical exchange rates FAQ"
>
    @php
        $faqs = [
            [
                'q' => 'What is FX Tracker?',
                'a' => 'FX Tracker is a currency rate tracker that provides live currency conversion tools, historical exchange rate charts, and API access for developers.',
            ],
            [
                'q' => 'How often are exchange rates updated?',
                'a' => 'Live conversion rates are refreshed regularly from our exchange rate provider. Historical charts are based on stored snapshots for business days.',
            ],
            [
                'q' => 'How far back does historical exchange data go?',
                'a' => 'The platform includes over 30 years of historical exchange rate data, covering major global currencies from 1994 onward.',
            ],
            [
                'q' => 'Can I compare different currency pairs?',
                'a' => 'Yes. Use the Historical Charts page to choose base and quote currencies, then switch time periods to compare long-term and short-term trends.',
            ],
            [
                'q' => 'Do you support a currency exchange API?',
                'a' => 'Yes. FX Tracker offers an API with plan-based access, including historical rates and additional analytics endpoints for higher tiers.',
            ],
            [
                'q' => 'How do I get an API key?',
                'a' => 'Create an account, sign in, and generate an API key from your profile page. Include it in the Authorization header for API requests.',
            ],
            [
                'q' => 'Is FX Tracker free to use?',
                'a' => 'Yes. Core conversion and charting features are available publicly, and API plans include a free tier with monthly request limits.',
            ],
            [
                'q' => 'Why are some dates missing in charts?',
                'a' => 'Historical datasets are based on business days and can exclude weekends and market holidays, which may create expected date gaps.',
            ],
        ];

        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['a'],
                ],
            ], $faqs),
        ];
    @endphp

    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mb-3 inline-flex items-center rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Help & Support</p>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Frequently Asked Questions</h1>
                <p class="mt-4 text-lg text-slate-600">Everything you need to know about our currency rate tracker, exchange tools, historical data, and API.</p>
            </div>
        </section>

        <section class="mx-auto max-w-4xl space-y-4">
            @foreach ($faqs as $item)
                <details class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <summary class="cursor-pointer list-none pr-8 text-lg font-semibold text-slate-900">
                        {{ $item['q'] }}
                    </summary>
                    <p class="mt-3 text-slate-600">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white px-6 py-8 text-center">
            <h2 class="text-2xl font-semibold text-slate-900">Still have questions?</h2>
            <p class="mt-2 text-slate-600">Explore detailed API docs or return to the converter and charts.</p>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('api-docs') }}" class="primary-btn">View API Docs</a>
                <a href="{{ route('home') }}" class="secondary-btn">Go to Converter</a>
                <a href="{{ route('charts.index') }}" class="secondary-btn">Open Charts</a>
            </div>
        </section>
    </div>

    <script type="application/ld+json">
        {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-layout>

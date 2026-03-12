<x-layout>
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-8">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-8">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">API Documentation</h1>
            <p class="mt-3 text-lg text-slate-600">Complete reference for FX Tracker's historical exchange rate API. All endpoints require a valid API key.</p>
        </div>

        <!-- Plan Tier Overview -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Endpoint Access by Plan</h2>
            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase text-slate-600">
                        <tr>
                            <th class="py-3 pl-6 pr-4">Endpoint</th>
                            <th class="py-3 pr-4 text-center">Free</th>
                            <th class="py-3 pr-4 text-center">Pro</th>
                            <th class="py-3 pr-6 text-center">Business</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $endpointMatrix = [
                                ['GET /history',    'Historical rates for a pair over a date range', true,  true,  true],
                                    ['GET /currencies', 'All supported currencies with names',            true,  true,  true],
                                ['GET /stats',      'Statistical summary: min, max, mean, std dev',  false, true,  true],
                                ['GET /bulk',       'All currencies for a single date',              false, true,  true],
                                ['GET /nearest',    'Nearest available rate to a given date',        false, true,  true],
                                ['GET /compare',    'Rate comparison between two dates',             false, true,  true],
                                ['GET /multi',      'One base to multiple targets on one date',      false, false, true],
                                ['GET /volatility', 'Annualised volatility and daily change stats',  false, false, true],
                                ['GET /export',     'Download full dataset as CSV or JSON',          false, false, true],
                            ];
                        @endphp
                        @foreach ($endpointMatrix as [$ep, $desc, $free, $pro, $biz])
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 pl-6 pr-4">
                                    <span class="font-mono text-sm font-semibold text-blue-600">{{ $ep }}</span>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $desc }}</p>
                                </td>
                                <td class="py-3 pr-4 text-center">
                                    @if($free) <span class="text-emerald-600">✓</span> @else <span class="text-slate-300">—</span> @endif
                                </td>
                                <td class="py-3 pr-4 text-center">
                                    @if($pro) <span class="text-emerald-600">✓</span> @else <span class="text-slate-300">—</span> @endif
                                </td>
                                <td class="py-3 pr-6 text-center">
                                    @if($biz) <span class="text-emerald-600">✓</span> @else <span class="text-slate-300">—</span> @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-slate-600">Upgrade your plan from your <a href="/profile" class="font-medium text-blue-600 hover:text-blue-700">profile page</a>. Plan quotas: Free 750/mo · Pro 10,000/mo · Business 50,000/mo.</p>
        </section>

        <!-- Getting Started -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Getting Started</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">FX Tracker provides programmatic access to over 30 years of historical exchange rate data (1994 – 11 March 2026). Query rates by currency pair and date range once you've generated an API key.</p>
                <ol class="list-decimal space-y-2 pl-6 text-slate-700">
                    <li><a href="/register" class="font-medium text-blue-600 hover:text-blue-700">Create an account</a></li>
                    <li>Navigate to your <a href="/profile" class="font-medium text-blue-600 hover:text-blue-700">profile page</a></li>
                    <li>Generate an API key</li>
                    <li>Include your API key in all requests</li>
                </ol>
            </div>
        </section>

        <!-- Authentication -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Authentication</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">All API requests must include your API key in the request headers:</p>
                <div class="rounded-lg bg-slate-900 p-4">
                    <pre class="overflow-x-auto text-sm text-slate-100"><code>Authorization: Bearer YOUR_API_KEY_HERE</code></pre>
                </div>
                <div class="rounded-lg border-l-4 border-amber-500 bg-amber-50 p-4">
                    <p class="text-sm font-medium text-amber-900"><strong>Important:</strong> Keep your API key secure. Never commit it to version control or expose it in client-side code.</p>
                </div>
            </div>
        </section>

        <!-- Base URL -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Base URL</h2>
            <div class="glass-panel p-6">
                <div class="rounded-lg bg-slate-900 p-4">
                    <pre class="overflow-x-auto text-sm text-slate-100"><code>{{ config('app.url') }}/api/v1</code></pre>
                </div>
            </div>
        </section>

        @php
            $req  = '<span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span>';
            $opt  = '<span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">No</span>';
            $getBadge = '<span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">GET</span>';
            $freeBadge = '<span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">All plans</span>';
            $proBadge = '<span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Pro +</span>';
            $bizBadge = '<span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Business</span>';
        @endphp

        <!-- Endpoints -->
        <section class="space-y-6">
            <h2 class="text-2xl font-bold text-slate-900">Endpoints</h2>

            {{-- ─── FREE TIER ───────────────────────────────────────────────────────── --}}
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500">All Plans</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

                <!-- GET /currencies -->
                <div class="glass-panel space-y-4 p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Supported Currencies</h3>
                            <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/currencies</p>
                        </div>
                        <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $freeBadge !!}</div>
                    </div>
                    <p class="text-slate-700">Returns all currency codes available in the historical dataset, along with their full names. Useful for populating dropdowns or validating <span class="font-mono">from</span> / <span class="font-mono">to</span> parameters before making other requests. Response is cached and very fast.</p>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-slate-900">Parameters</h4>
                        <p class="text-sm text-slate-600">None required.</p>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-slate-900">Example</h4>
                        <div class="rounded-lg bg-slate-900 p-4">
                            <pre class="overflow-x-auto text-sm text-slate-100"><code>curl "{{ config('app.url') }}/api/v1/currencies" \
      -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
                        </div>
                    </div>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
      "success": true,
      "data": {
        "count": 50,
        "currencies": [
          { "code": "AED", "name": "UAE Dirham" },
          { "code": "AUD", "name": "Australian Dollar" },
          { "code": "CAD", "name": "Canadian Dollar" },
          { "code": "EUR", "name": "Euro" },
          { "code": "GBP", "name": "Pound Sterling" },
          { "code": "USD", "name": "US Dollar" },
          "..."
        ]
      }
    }</code></pre>
                    </div>
                </div>

            <!-- GET /history -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Historical Exchange Rates</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/history</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $freeBadge !!}</div>
                </div>
                <p class="text-slate-700">Retrieve historical exchange rates for a currency pair over a date range.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency code (e.g. GBP)</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency code (e.g. USD)</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">start_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">Inclusive start date — YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">end_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">Inclusive end date — YYYY-MM-DD, must be ≥ start_date</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>curl "{{ config('app.url') }}/api/v1/history?from=GBP&to=USD&start_date=2026-02-01&end_date=2026-03-01" \
  -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
                    </div>
                </div>
                <div class="rounded-lg bg-slate-900 p-4">
                    <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from": "GBP", "to": "USD", "base_dataset": "USD",
    "start_date": "2026-02-01", "end_date": "2026-03-01", "count": 20,
    "rates": [
      { "date": "2026-02-01", "rate": 1.258341 },
      { "date": "2026-02-02", "rate": 1.263118 }
    ]
  }
}</code></pre>
                </div>
                <div class="rounded-lg border-l-4 border-blue-500 bg-blue-50 p-4">
                    <p class="text-sm text-blue-900"><strong>Note:</strong> The dataset is USD-based. Cross-rates for non-USD pairs are calculated on the fly. Identical <span class="font-mono">from</span> and <span class="font-mono">to</span> always returns <span class="font-mono">1.0</span>.</p>
                </div>
            </div>

            {{-- ─── PRO TIER ────────────────────────────────────────────────────────── --}}
            <div class="flex items-center gap-3 pt-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-semibold uppercase tracking-widest text-blue-500">Pro &amp; Business</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <!-- GET /stats -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Statistical Summary</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/stats</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $proBadge !!}</div>
                </div>
                <p class="text-slate-700">Returns min, max, mean, median, standard deviation, range, total change, and daily movement stats for a currency pair over a date range.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">start_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">end_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD, must be ≥ start_date</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from": "GBP", "to": "USD",
    "start_date": "2026-01-01", "end_date": "2026-03-01",
    "data_points": 41,
    "min": 1.228406, "max": 1.278912, "mean": 1.251034,
    "median": 1.249871, "std_dev": 0.012843, "range": 0.050506,
    "total_change": 0.019341, "total_change_pct": 1.5623,
    "daily_changes": {
      "count": 40, "average": 0.0391, "max": 0.8912, "min": -0.6134
    }
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /bulk -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Bulk Rates — All Currencies</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/bulk</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $proBadge !!}</div>
                </div>
                <p class="text-slate-700">Returns exchange rates for all available currencies from a given base on a single date. Up to 50 currencies per request.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">base</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $opt !!}</td><td class="py-3 text-slate-700">Base currency code — defaults to USD</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "date": "2026-03-01", "base": "GBP", "count": 50,
    "rates": {
      "AUD": 2.052134, "CAD": 1.791028, "CHF": 1.128344,
      "EUR": 1.172891, "JPY": 192.341, "USD": 1.261027, "..."
    }
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /nearest -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Nearest Rate</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/nearest</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $proBadge !!}</div>
                </div>
                <p class="text-slate-700">Returns the closest available exchange rate to a requested date. Searches up to 30 days before and after. Useful for weekends, bank holidays, and gaps in the dataset.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from": "GBP", "to": "USD",
    "requested_date": "2025-12-25",
    "actual_date": "2025-12-24",
    "days_apart": 1,
    "rate": 1.254812
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /compare -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Compare Two Dates</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/compare</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $proBadge !!}</div>
                </div>
                <p class="text-slate-700">Compares a currency pair rate on two specific dates, returning the absolute change and percentage change. Useful for year-over-year or any custom period analysis.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">Primary date — YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">compare_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">Date to compare against — YYYY-MM-DD, must differ from date</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from": "GBP", "to": "USD",
    "date":         { "date": "2026-03-01", "rate": 1.261027 },
    "compare_date": { "date": "2025-03-01", "rate": 1.268341 },
    "change": -0.007314,
    "change_pct": -0.5768,
    "direction": "down"
  }
}</code></pre>
                    </div>
                </div>
            </div>

            {{-- ─── BUSINESS TIER ───────────────────────────────────────────────────── --}}
            <div class="flex items-center gap-3 pt-4">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs font-semibold uppercase tracking-widest text-purple-500">Business Only</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <!-- GET /multi -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Multi-Currency Rates</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/multi</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $bizBadge !!}</div>
                </div>
                <p class="text-slate-700">Returns rates from one base currency to up to 20 selected target currencies on a single date. More efficient than multiple <span class="font-mono">/history</span> calls when you need several targets at once.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">targets[]</td><td class="py-3 pr-4 text-slate-600">array</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">1–20 ISO 4217 target currency codes</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>curl "{{ config('app.url') }}/api/v1/multi?from=GBP&targets[]=USD&targets[]=EUR&targets[]=JPY&date=2026-03-01" \
  -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
                    </div>
                </div>
                <div class="rounded-lg bg-slate-900 p-4">
                    <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "date": "2026-03-01", "from": "GBP", "count": 3,
    "rates": {
      "USD": 1.261027,
      "EUR": 1.172891,
      "JPY": 192.341
    }
  }
}</code></pre>
                </div>
            </div>

            <!-- GET /volatility -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Volatility Analysis</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/volatility</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $bizBadge !!}</div>
                </div>
                <p class="text-slate-700">Returns annualised volatility, daily log-return standard deviation, 30-day rolling volatility, and detailed daily change statistics. Volatility is calculated using daily log returns annualised over 252 trading days.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">start_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">end_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD, must be ≥ start_date</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from": "GBP", "to": "USD",
    "start_date": "2025-01-01", "end_date": "2026-03-01", "data_points": 263,
    "daily_volatility": { "std_dev": "0.388421%", "std_dev_raw": 0.00388421 },
    "annualised_volatility": "6.1677%",
    "rolling_30d_annualised_volatility": "5.9342%",
    "daily_changes": {
      "count": 262, "average": "0.0083%",
      "max_gain": "1.2341%", "max_loss": "-0.9812%",
      "up_days": 138, "down_days": 119, "flat_days": 5
    },
    "period_performance": {
      "open": 1.249412, "close": 1.261027,
      "change": 0.011615, "change_pct": "0.9297%"
    }
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- GET /export -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900">Export Dataset</h3>
                        <p class="mt-1 font-mono text-sm text-slate-500">GET /api/v1/export</p>
                    </div>
                    <div class="flex shrink-0 gap-2">{!! $getBadge !!}{!! $bizBadge !!}</div>
                </div>
                <p class="text-slate-700">Download the full exchange rate dataset for a currency pair and date range as CSV or JSON. The response is streamed as a file download. Each request counts as one API call regardless of the number of data points.</p>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600"><tr><th class="py-3 pr-4">Parameter</th><th class="py-3 pr-4">Type</th><th class="py-3 pr-4">Required</th><th class="py-3">Description</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">from</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 base currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">to</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">ISO 4217 target currency</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">start_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">end_date</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $req !!}</td><td class="py-3 text-slate-700">YYYY-MM-DD, must be ≥ start_date</td></tr>
                                <tr><td class="py-3 pr-4 font-mono text-blue-600">format</td><td class="py-3 pr-4 text-slate-600">string</td><td class="py-3 pr-4">{!! $opt !!}</td><td class="py-3 text-slate-700"><span class="font-mono">csv</span> or <span class="font-mono">json</span> — defaults to json</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example — CSV</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>curl "{{ config('app.url') }}/api/v1/export?from=GBP&to=USD&start_date=2020-01-01&end_date=2026-03-01&format=csv" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -o gbp_usd_2020_2026.csv</code></pre>
                    </div>
                </div>
                <div class="rounded-lg border-l-4 border-purple-500 bg-purple-50 p-4">
                    <p class="text-sm text-purple-900">The CSV includes a UTF-8 BOM for Excel compatibility. JSON exports include metadata such as record count and export timestamp.</p>
                </div>
            </div>
        </section>

        <!-- Error Responses -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Error Responses</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">The API uses standard HTTP response codes to indicate success or failure:</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase text-slate-600">
                            <tr>
                                <th class="py-3 pr-4">Status Code</th>
                                <th class="py-3">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-3 pr-4 font-mono text-emerald-600">200</td>
                                <td class="py-3 text-slate-700">Success - Request completed successfully</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-amber-600">400</td>
                                <td class="py-3 text-slate-700">Bad Request - Invalid parameters</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-red-600">401</td>
                                <td class="py-3 text-slate-700">Unauthorized - Invalid or missing API key</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-red-600">403</td>
                                <td class="py-3 text-slate-700">Forbidden - Your plan does not include this endpoint</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-red-600">404</td>
                                <td class="py-3 text-slate-700">Not Found - Resource not found</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-amber-600">422</td>
                                <td class="py-3 text-slate-700">Validation Error - Missing or invalid date/currency parameters</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-red-600">429</td>
                                <td class="py-3 text-slate-700">Too Many Requests - Rate limit exceeded</td>
                            </tr>
                            <tr>
                                <td class="py-3 pr-4 font-mono text-red-600">500</td>
                                <td class="py-3 text-slate-700">Internal Server Error - Something went wrong</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Error Response Format</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                                                <pre class="overflow-x-auto text-sm text-slate-100"><code>{
    "message": "The end date field must be a date after or equal to start date.",
    "errors": {
        "end_date": [
            "The end date field must be a date after or equal to start date."
        ]
    }
}</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rate Limiting -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Rate Limiting</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">API requests are limited by monthly plan allowance and short-term protection rules to ensure fair usage and platform stability:</p>
                <ul class="list-disc space-y-2 pl-6 text-slate-700">
                    <li><strong>Free:</strong> 750 requests per month</li>
                    <li><strong>Pro:</strong> 10,000 requests per month</li>
                    <li><strong>Business:</strong> 50,000 requests per month</li>
                    <li>Exceeding your quota returns a 429 status code with reset timing</li>
                </ul>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Quota Response Example</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": false,
  "error": "Monthly quota exceeded",
  "message": "Your Free plan includes 750 API requests per month. Upgrade to continue.",
  "plan": "free",
  "monthly_limit": 750,
  "requests_used": 750,
  "requests_remaining": 0,
  "quota_resets_at": "2026-04-01T00:00:00+00:00"
}</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- Supported Currencies -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Supported Currencies</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">The historical API supports the <strong>50 currencies</strong> available in the imported USD-based dataset, including:</p>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @php
                        $popularCurrencies = [
                            'USD' => 'United States Dollar',
                            'EUR' => 'Euro',
                            'GBP' => 'British Pound Sterling',
                            'JPY' => 'Japanese Yen',
                            'AUD' => 'Australian Dollar',
                            'CAD' => 'Canadian Dollar',
                            'CHF' => 'Swiss Franc',
                            'CNY' => 'Chinese Yuan',
                            'INR' => 'Indian Rupee',
                            'SGD' => 'Singapore Dollar',
                            'HKD' => 'Hong Kong Dollar',
                            'NZD' => 'New Zealand Dollar'
                        ];
                    @endphp
                    @foreach ($popularCurrencies as $code => $name)
                        <div class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <span class="font-mono font-semibold text-blue-600">{{ $code }}</span>
                            <span class="text-slate-600">{{ $name }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-sm text-slate-600">Historical data covers <strong>1994 to 11 March 2026</strong>. Coverage may vary by currency — query only currencies present in your stored dataset.</p>
            </div>
        </section>

        <!-- Support -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Support</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">Need help integrating the API?</p>
                <div class="flex flex-wrap gap-3">
                    <a href="/api-terms" class="secondary-btn px-4 py-2 text-sm">API Terms of Service</a>
                    <a href="/privacy-policy" class="secondary-btn px-4 py-2 text-sm">Privacy Policy</a>
                </div>
            </div>
        </section>
    </div>
</x-layout>

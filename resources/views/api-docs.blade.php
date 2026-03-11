<x-layout>
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-8">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-8">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">API Documentation</h1>
            <p class="mt-3 text-lg text-slate-600">Complete guide to accessing FX Tracker's historical exchange rate data and live conversion API.</p>
        </div>

        <!-- Getting Started -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Getting Started</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">FX Tracker provides programmatic access to 32 years of historical exchange rate data and live conversion endpoints. To get started:</p>
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

        <!-- Endpoints -->
        <section class="space-y-6">
            <h2 class="text-2xl font-bold text-slate-900">Endpoints</h2>

            <!-- Convert Endpoint -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between">
                    <h3 class="text-xl font-semibold text-slate-900">Currency Conversion</h3>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">GET</span>
                </div>
                
                <p class="text-slate-700">Convert an amount from one currency to another using live exchange rates.</p>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Endpoint</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>GET /api/v1/convert</code></pre>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Query Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600">
                                <tr>
                                    <th class="py-3 pr-4">Parameter</th>
                                    <th class="py-3 pr-4">Type</th>
                                    <th class="py-3 pr-4">Required</th>
                                    <th class="py-3">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">from</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span></td>
                                    <td class="py-3 text-slate-700">Source currency code (e.g., USD, EUR, GBP)</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">to</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span></td>
                                    <td class="py-3 text-slate-700">Target currency code (e.g., USD, EUR, GBP)</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">amount</td>
                                    <td class="py-3 pr-4 text-slate-600">number</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span></td>
                                    <td class="py-3 text-slate-700">Amount to convert (must be positive)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Request</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>curl -X GET "{{ config('app.url') }}/api/v1/convert?from=USD&to=EUR&amount=100" \
  -H "Authorization: Bearer YOUR_API_KEY_HERE" \
  -H "Accept: application/json"</code></pre>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from_currency": "USD",
    "to_currency": "EUR",
    "amount": 100,
    "exchange_rate": 0.92,
    "converted_amount": 92.00,
    "timestamp": "2026-03-09T12:34:56Z"
  }
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Historical Rates Endpoint -->
            <div class="glass-panel space-y-4 p-6">
                <div class="flex items-start justify-between">
                    <h3 class="text-xl font-semibold text-slate-900">Historical Exchange Rates</h3>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">GET</span>
                </div>
                
                <p class="text-slate-700">Retrieve historical exchange rates for a specific currency pair.</p>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Endpoint</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>GET /api/v1/history</code></pre>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Query Parameters</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 text-xs uppercase text-slate-600">
                                <tr>
                                    <th class="py-3 pr-4">Parameter</th>
                                    <th class="py-3 pr-4">Type</th>
                                    <th class="py-3 pr-4">Required</th>
                                    <th class="py-3">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">from</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span></td>
                                    <td class="py-3 text-slate-700">Base currency code</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">to</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Yes</span></td>
                                    <td class="py-3 text-slate-700">Target currency code</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">start_date</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">No</span></td>
                                    <td class="py-3 text-slate-700">Start date (YYYY-MM-DD). Defaults to 30 days ago.</td>
                                </tr>
                                <tr>
                                    <td class="py-3 pr-4 font-mono text-blue-600">end_date</td>
                                    <td class="py-3 pr-4 text-slate-600">string</td>
                                    <td class="py-3 pr-4"><span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">No</span></td>
                                    <td class="py-3 text-slate-700">End date (YYYY-MM-DD). Defaults to today.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Request</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>curl -X GET "{{ config('app.url') }}/api/v1/history?from=USD&to=EUR&start_date=2026-02-01&end_date=2026-03-01" \
  -H "Authorization: Bearer YOUR_API_KEY_HERE" \
  -H "Accept: application/json"</code></pre>
                    </div>
                </div>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Example Response</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>{
  "success": true,
  "data": {
    "from_currency": "USD",
    "to_currency": "EUR",
    "rates": [
      {
        "date": "2026-02-01",
        "rate": 0.91
      },
      {
        "date": "2026-02-02",
        "rate": 0.92
      }
    ]
  }
}</code></pre>
                    </div>
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
                                <td class="py-3 pr-4 font-mono text-red-600">404</td>
                                <td class="py-3 text-slate-700">Not Found - Resource not found</td>
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
  "success": false,
  "error": {
    "code": "INVALID_CURRENCY",
    "message": "The specified currency code is not supported"
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
                <p class="text-slate-700">API requests are rate limited to ensure fair usage and platform stability:</p>
                <ul class="list-disc space-y-2 pl-6 text-slate-700">
                    <li><strong>60 requests per minute</strong> per API key</li>
                    <li>Rate limit headers are included in every response</li>
                    <li>Exceeding the limit returns a 429 status code</li>
                </ul>

                <div class="space-y-2">
                    <h4 class="font-semibold text-slate-900">Rate Limit Headers</h4>
                    <div class="rounded-lg bg-slate-900 p-4">
                        <pre class="overflow-x-auto text-sm text-slate-100"><code>X-RateLimit-Limit: 60
X-RateLimit-Remaining: 58
X-RateLimit-Reset: 1709989200</code></pre>
                    </div>
                </div>
            </div>
        </section>

        <!-- Supported Currencies -->
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-slate-900">Supported Currencies</h2>
            <div class="glass-panel space-y-4 p-6">
                <p class="text-slate-700">The API supports <strong>162 currency codes</strong> including:</p>
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
                <p class="text-sm text-slate-600">...and 150+ more. View the complete list in the currency converter dropdown.</p>
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

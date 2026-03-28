<x-layout header="header-blog-post" page-type="article">
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-10">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Account Overview</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Your Profile Workspace</h1>
            <p class="mt-2 text-sm text-slate-600">Manage your identity, API access, and conversion activity in one
                place.</p>
        </section>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-6 py-4">
                <div class="flex items-start">
                    <svg class="mr-3 mt-0.5 h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-emerald-800">Success</h4>
                        <p class="mt-1 text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Profile</h2>
                    <p class="mt-1 text-sm text-slate-600">Manage your account information</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="primary-btn py-2.5">
                    Edit Profile
                </a>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-slate-700">Full Name</h3>
                        <p class="mt-1 text-xs text-slate-500">Your display name</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-slate-900">{{ $user->name }}</p>
                    </div>
                </div>

                <div class="border-t border-slate-200"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-slate-700">Email Address</h3>
                        <p class="mt-1 text-xs text-slate-500">Your account email</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-slate-900">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="border-t border-slate-200"></div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <h3 class="text-sm font-semibold text-slate-700">Member Since</h3>
                        <p class="mt-1 text-xs text-slate-500">Account creation date</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-lg text-slate-900">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 border-b border-slate-200 pb-4">
                <h2 class="text-xl font-semibold text-slate-900">API Access</h2>
                <p class="mt-1 text-sm text-slate-600">Manage your API key for programmatic access</p>
            </div>

            <div class="mb-6 grid gap-4 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 lg:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Current Plan</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <span
                            class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">{{ $user->apiPlanName() }}</span>
                        <span class="text-sm text-slate-600">{{ number_format($currentApiUsage) }} /
                            {{ number_format($currentApiLimit) }} requests used this month</span>
                    </div>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full rounded-full bg-blue-600"
                            style="width: {{ min(100, $currentApiLimit > 0 ? ($currentApiUsage / $currentApiLimit) * 100 : 0) }}%">
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">Monthly quotas reset automatically on the first day of each
                        month.</p>
                    <div class="mt-4">
                        <a href="{{ route('profile.invoices') }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">View
                            Invoices</a>
                    </div>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Upgrade</p>
                    <p class="mt-2 text-sm text-amber-900">Upgrade instantly with secure Stripe checkout for larger
                        monthly API quotas.</p>
                    @if ($user->stripe_customer_id)
                        <form method="POST" action="{{ route('billing.portal') }}" class="mt-3">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs font-semibold text-amber-800 transition hover:bg-amber-100">Manage
                                billing</button>
                        </form>
                    @endif
                </div>
            </div>

            @if (session('billing_error'))
                <div class="mb-6 rounded-xl border border-red-300 bg-red-50 px-6 py-4">
                    <p class="text-sm font-medium text-red-800">{{ session('billing_error') }}</p>
                </div>
            @endif

            @if (session('api_key'))
                <div class="mb-6 rounded-xl border border-blue-300 bg-blue-50 px-6 py-4">
                    <div class="flex items-start">
                        <svg class="mr-3 mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="mb-2 text-sm font-semibold text-blue-800">Your API Key (Save it now!)</h4>
                            <div class="mb-2 rounded border border-blue-200 bg-white p-3">
                                <code
                                    class="break-all text-sm font-mono text-slate-900">{{ session('api_key') }}</code>
                            </div>
                            <p class="text-xs text-blue-700">⚠️ This key will only be shown once. Copy it now and store
                                it securely.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($user->api_key)
                <div class="space-y-4">
                    <div class="rounded-xl border border-emerald-300 bg-emerald-50 p-4">
                        <div class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-semibold text-emerald-800">API Key Active</span>
                        </div>
                        <p class="mt-2 text-xs text-emerald-700">You have an active API key. Use it in your requests
                            with
                            the header: <code class="rounded bg-emerald-100 px-1 py-0.5">Authorization: Bearer
                                YOUR_KEY</code></p>
                    </div>

                    <div class="flex items-center gap-4">
                        <form method="POST" action="{{ route('profile.api-key.generate') }}">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('This will invalidate your current API key. Continue?')"
                                class="rounded-xl bg-amber-600 px-5 py-2.5 font-semibold text-white transition hover:bg-amber-500">
                                Regenerate Key
                            </button>
                        </form>

                        <form method="POST" action="{{ route('profile.api-key.revoke') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to revoke your API key? This cannot be undone.')"
                                class="rounded-xl bg-red-600 px-5 py-2.5 font-semibold text-white transition hover:bg-red-500">
                                Revoke Key
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-slate-600">You don't have an API key yet. Generate one to access the
                        historical
                        exchange rate API programmatically.</p>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="mb-2 text-sm font-semibold text-slate-700">API Endpoint</h4>
                        <code class="text-xs font-mono text-slate-800">GET
                            {{ config('app.url') }}/api/v1/history?from=GBP&to=USD&start_date=2026-02-01&end_date=2026-03-01</code>
                    </div>

                    <form method="POST" action="{{ route('profile.api-key.generate') }}">
                        @csrf
                        <button type="submit" class="primary-btn py-2.5">
                            Generate API Key
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 border-b border-slate-200 pb-4">
                <h2 class="text-xl font-semibold text-slate-900">API Plans</h2>
                <p class="mt-1 text-sm text-slate-600">Choose a monthly request allowance that fits your usage.</p>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                @foreach ($apiPlans as $planKey => $plan)
                    <div
                        class="rounded-2xl border {{ $user->api_plan === $planKey ? 'border-blue-300 bg-blue-50/60' : 'border-slate-200 bg-white' }} p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $plan['name'] }}</h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $plan['description'] }}</p>
                            </div>
                            @if ($user->api_plan === $planKey)
                                <span
                                    class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">Current</span>
                            @endif
                        </div>

                        <p class="mt-5 text-3xl font-bold text-slate-900">{{ $plan['price_label'] }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ number_format($plan['monthly_requests']) }}
                            historical API requests per month</p>

                        <ul class="mt-4 space-y-2 text-sm text-slate-600">
                            <li>Historical data by date range</li>
                            <li>Currency pair queries</li>
                            <li>Bearer token authentication</li>
                        </ul>

                        <div class="mt-5">
                            @if ($user->api_plan === $planKey)
                                <span
                                    class="inline-flex rounded-lg border border-blue-300 bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700">Current
                                    plan</span>
                            @elseif (!empty($plan['stripe_price_id']))
                                <form method="POST" action="{{ route('billing.checkout') }}">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $planKey }}">
                                    <button type="submit"
                                        class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Upgrade
                                        with Stripe</button>
                                </form>
                            @else
                                <span
                                    class="inline-flex rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">Included</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 border-b border-slate-200 pb-4">
                <h2 class="text-xl font-semibold text-slate-900">Previous Conversions</h2>
                <p class="mt-1 text-sm text-slate-600">See all of your previous conversions here</p>
            </div>
            <div class="table-shell overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                Amount</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                From</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                To</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                Result</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($conversions as $conversion)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ number_format($conversion['amount'], 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $conversion['from_currency'] }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                    {{ $conversion['to_currency'] }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-blue-700">
                                    {{ number_format($conversion['conversion_result'], 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                    {{ $conversion['created_at']->format('M j, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                    No conversions yet. Start by converting currencies!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layout>

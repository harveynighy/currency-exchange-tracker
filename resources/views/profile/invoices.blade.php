<x-layout>
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-10">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Billing</p>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Invoices</h1>
            <p class="mt-2 text-sm text-slate-600">Review your paid API plan invoices and download Stripe-hosted receipts.</p>
        </section>

        <div class="glass-panel p-8 sm:p-10">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Invoice History</h2>
                    <p class="mt-1 text-sm text-slate-600">All invoices generated for your account.</p>
                </div>
                <a href="{{ route('profile.show') }}" class="secondary-btn py-2.5">Back to Profile</a>
            </div>

            <div class="table-shell overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($invoices as $invoice)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-mono text-slate-700">{{ $invoice->stripe_invoice_id }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ strtoupper($invoice->status ?? 'unknown') }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                    {{ strtoupper($invoice->currency ?? 'GBP') }} {{ number_format(($invoice->amount_paid > 0 ? $invoice->amount_paid : $invoice->amount_due) / 100, 2) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $invoice->period_start?->format('M j, Y') ?? '-' }} to {{ $invoice->period_end?->format('M j, Y') ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $invoice->created_at->format('M j, Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if ($invoice->hosted_invoice_url)
                                        <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-blue-600 hover:text-blue-700">View</a>
                                    @endif
                                    @if ($invoice->invoice_pdf)
                                        <a href="{{ $invoice->invoice_pdf }}" target="_blank" class="ml-3 text-blue-600 hover:text-blue-700">PDF</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">No invoices yet. Once you subscribe to a paid plan, invoices will appear here.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-layout>
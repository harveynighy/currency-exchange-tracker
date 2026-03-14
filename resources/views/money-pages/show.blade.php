<x-layout
    :title="$from . ' to ' . $to . ' Exchange Rate, Charts & Converter | FX Tracker'"
    :description="'Track ' . $from . ' to ' . $to . ' exchange rates with live conversion and historical FX charts on FX Tracker.'"
    :keywords="strtolower($from . ' to ' . $to . ', ' . $from . ' ' . $to . ' exchange rate, ' . $from . ' ' . $to . ' converter, currency exchange')"
>
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => "How can I convert {$from} to {$to}?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Use our converter to calculate {$from} to {$to} instantly using current market exchange data.",
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => "Can I view historical {$from}/{$to} trends?",
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => "Yes. Open the historical charts page with {$from}/{$to} preselected to analyze short- and long-term movement.",
                    ],
                ],
            ],
        ];
    @endphp

    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mb-3 inline-flex rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Currency Pair</p>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">{{ $from }} to {{ $to }} Exchange Rate</h1>
                <p class="mt-4 text-lg text-slate-600">Convert {{ $fromName }} to {{ $toName }} and explore historical exchange-rate trends.</p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home', ['from' => $from, 'to' => $to]) }}" class="primary-btn">Convert {{ $from }} → {{ $to }}</a>
                    <a href="{{ route('charts.index', ['from' => $from, 'to' => $to]) }}" class="secondary-btn">View {{ $from }}/{{ $to }} Chart</a>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Pair Overview</h2>
                <p class="mt-2 text-slate-600">{{ $from }} is the base currency and {{ $to }} is the quote currency in this pair.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Live Conversion</h2>
                <p class="mt-2 text-slate-600">Use our converter for up-to-date pricing and quick value checks.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Historical Trends</h2>
                <p class="mt-2 text-slate-600">Analyze trend direction and volatility using multiple time ranges.</p>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white px-6 py-10 shadow-sm sm:px-10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $from }}/{{ $to }} Historical Chart</h2>
                <a href="{{ route('charts.index', ['from' => $from, 'to' => $to]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Open full chart page →</a>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                <button type="button" data-period="7d" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">7 Days</button>
                <button type="button" data-period="30d" class="period-btn active rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">30 Days</button>
                <button type="button" data-period="90d" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">90 Days</button>
                <button type="button" data-period="1y" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">1 Year</button>
                <button type="button" data-period="5y" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">5 Years</button>
                <button type="button" data-period="10y" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">10 Years</button>
                <button type="button" data-period="all" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">All Time</button>
            </div>

            <div id="pair-chart-loading" class="mt-6 hidden rounded-xl border border-slate-200 bg-slate-50 p-12 text-center">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
                <p class="mt-3 text-sm font-medium text-slate-600">Loading historical data...</p>
            </div>

            <div id="pair-chart-container" class="mt-6 rounded-xl border border-slate-200 bg-white p-4 sm:p-6">
                <div style="height: 480px; position: relative;">
                    <canvas id="pair-rates-chart" class="w-full" style="max-height: 480px;"></canvas>
                </div>
            </div>

            <div id="pair-chart-stats" class="mt-4 grid gap-4 sm:grid-cols-4">
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">Current Rate</p>
                    <p id="pair-stat-current" class="mt-1 text-lg font-semibold text-slate-900">-</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">Highest</p>
                    <p id="pair-stat-high" class="mt-1 text-lg font-semibold text-emerald-600">-</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">Lowest</p>
                    <p id="pair-stat-low" class="mt-1 text-lg font-semibold text-red-600">-</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-xs font-medium text-slate-500">Average</p>
                    <p id="pair-stat-avg" class="mt-1 text-lg font-semibold text-slate-900">-</p>
                </div>
            </div>
        </section>

        @if (!empty($relatedPairs))
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-900">Related exchange pairs</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($relatedPairs as $pair)
                        <a href="{{ route('money.show', ['from' => $pair['from'], 'to' => $pair['to']]) }}"
                           class="rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:text-blue-700">
                            {{ $pair['from'] }} to {{ $pair['to'] }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script type="application/ld+json">
        {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const pairFrom = @json($from);
        const pairTo = @json($to);

        let pairChart = null;
        let pairCurrentPeriod = '30d';

        function setActivePeriodButton(period) {
            document.querySelectorAll('.period-btn').forEach(btn => {
                const isActive = btn.dataset.period === period;

                btn.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-300', 'hover:bg-slate-50');

                if (isActive) {
                    btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-300', 'hover:bg-slate-50');
                    btn.classList.add('active', 'bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
                }
            });
        }

        function updatePairStats(data) {
            if (!data.length) {
                document.getElementById('pair-stat-current').textContent = '-';
                document.getElementById('pair-stat-high').textContent = '-';
                document.getElementById('pair-stat-low').textContent = '-';
                document.getElementById('pair-stat-avg').textContent = '-';
                return;
            }

            const rates = data.map(d => d.rate);
            const current = rates[rates.length - 1];
            const high = Math.max(...rates);
            const low = Math.min(...rates);
            const avg = rates.reduce((a, b) => a + b, 0) / rates.length;

            document.getElementById('pair-stat-current').textContent = current.toFixed(6);
            document.getElementById('pair-stat-high').textContent = high.toFixed(6);
            document.getElementById('pair-stat-low').textContent = low.toFixed(6);
            document.getElementById('pair-stat-avg').textContent = avg.toFixed(6);
        }

        function renderPairChart(data) {
            const ctx = document.getElementById('pair-rates-chart').getContext('2d');

            if (pairChart) {
                pairChart.destroy();
            }

            pairChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: `${pairFrom}/${pairTo}`,
                        data: data.map(d => d.rate),
                        borderColor: 'rgb(37, 99, 235)',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.1,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Rate: ${context.parsed.y.toFixed(6)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            ticks: {
                                maxTicksLimit: 10
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Exchange Rate'
                            }
                        }
                    }
                }
            });
        }

        async function loadPairChartData() {
            document.getElementById('pair-chart-loading').classList.remove('hidden');
            document.getElementById('pair-chart-container').classList.add('hidden');

            try {
                const response = await fetch(`/charts/data?from=${pairFrom}&to=${pairTo}&period=${pairCurrentPeriod}`);
                const result = await response.json();

                if (result.success) {
                    renderPairChart(result.data);
                    updatePairStats(result.data);
                }
            } catch (error) {
                console.error('Error loading pair chart data:', error);
            } finally {
                document.getElementById('pair-chart-loading').classList.add('hidden');
                document.getElementById('pair-chart-container').classList.remove('hidden');
            }
        }

        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                pairCurrentPeriod = btn.dataset.period;
                setActivePeriodButton(pairCurrentPeriod);
                loadPairChartData();
            });
        });

        setActivePeriodButton(pairCurrentPeriod);
        loadPairChartData();
    </script>
</x-layout>

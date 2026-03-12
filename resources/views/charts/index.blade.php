<x-layout>
    <div class="w-full space-y-10">
        <!-- Hero Section -->
        <section class="rounded-3xl border border-blue-100 bg-gradient-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mb-3 inline-flex items-center rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Data through 11 Mar 2026</p>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Historical Exchange Rate Analysis</h1>
                <p class="mt-4 text-lg text-slate-600">Explore over three decades of currency exchange rate trends and patterns across 50+ global currencies. Data covers 1994 to 11 March 2026.</p>
            </div>
        </section>

        <!-- Chart Controls -->
        <section class="rounded-3xl border border-slate-200 bg-white px-6 py-10 shadow-sm sm:px-10">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-center text-2xl font-bold text-slate-900 sm:text-3xl">Currency Pair Analysis</h2>
                
                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    @php
                        $currencies = config('currencies.supported');
                        $popularCodes = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR', 'SGD'];
                    @endphp

                    <!-- From Currency -->
                    <div>
                        <label for="chart-from" class="mb-2 block text-sm font-medium text-slate-600">Base Currency</label>
                        <select id="chart-from" class="form-input w-full">
                            @foreach ($popularCodes as $code)
                                @if(isset($currencies[$code]))
                                    <option value="{{ $code }}" {{ $code === $defaultFrom ? 'selected' : '' }}>
                                        {{ $code }} - {{ $currencies[$code] }}
                                    </option>
                                @endif
                            @endforeach
                            <optgroup label="All Currencies">
                                @foreach ($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ $code === $defaultFrom ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <!-- To Currency -->
                    <div>
                        <label for="chart-to" class="mb-2 block text-sm font-medium text-slate-600">Quote Currency</label>
                        <select id="chart-to" class="form-input w-full">
                            @foreach ($popularCodes as $code)
                                @if(isset($currencies[$code]))
                                    <option value="{{ $code }}" {{ $code === $defaultTo ? 'selected' : '' }}>
                                        {{ $code }} - {{ $currencies[$code] }}
                                    </option>
                                @endif
                            @endforeach
                            <optgroup label="All Currencies">
                                @foreach ($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ $code === $defaultTo ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Time Period Selector -->
                <div class="mt-6">
                    <label class="mb-3 block text-sm font-medium text-slate-600">Time Period</label>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="updatePeriod('7d')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">7 Days</button>
                        <button onclick="updatePeriod('30d')" class="period-btn active rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">30 Days</button>
                        <button onclick="updatePeriod('90d')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">90 Days</button>
                        <button onclick="updatePeriod('1y')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">1 Year</button>
                        <button onclick="updatePeriod('5y')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">5 Years</button>
                        <button onclick="updatePeriod('10y')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">10 Years</button>
                        <button onclick="updatePeriod('all')" class="period-btn rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">All Time</button>
                    </div>
                </div>

                <!-- Chart -->
                <div class="mt-8">
                    <div id="chart-loading" class="hidden rounded-xl border border-slate-200 bg-slate-50 p-12 text-center">
                        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-blue-600 border-r-transparent"></div>
                        <p class="mt-3 text-sm font-medium text-slate-600">Loading historical data...</p>
                    </div>
                    
                    <div id="chart-container" class="rounded-xl border border-slate-200 bg-white p-4 sm:p-6">
                        <div style="height: 600px; position: relative;">
                            <canvas id="rates-chart" class="w-full" style="max-height: 600px;"></canvas>
                        </div>
                    </div>

                    <div id="chart-stats" class="mt-4 grid gap-4 sm:grid-cols-4">
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Current Rate</p>
                            <p id="stat-current" class="mt-1 text-lg font-semibold text-slate-900">-</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Highest</p>
                            <p id="stat-high" class="mt-1 text-lg font-semibold text-emerald-600">-</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Lowest</p>
                            <p id="stat-low" class="mt-1 text-lg font-semibold text-red-600">-</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <p class="text-xs font-medium text-slate-500">Average</p>
                            <p id="stat-avg" class="mt-1 text-lg font-semibold text-slate-900">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Converter Link -->
        <section class="rounded-2xl border border-slate-200 bg-white px-6 py-8 text-center">
            <h3 class="text-xl font-semibold text-slate-900">Need a Quick Conversion?</h3>
            <p class="mt-2 text-slate-600">Convert currencies using today's live exchange rates</p>
            <a href="/" class="mt-4 inline-block rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition hover:bg-blue-700">Go to Converter</a>
        </section>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <script>
        let chart = null;
        let currentPeriod = '30d';

        function updatePeriod(period) {
            currentPeriod = period;
            
            // Update button styles
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
            });
            
            event.target.classList.remove('bg-white', 'text-slate-700', 'border-slate-300');
            event.target.classList.add('active', 'bg-blue-600', 'text-white', 'border-blue-600');
            
            loadChartData();
        }

        async function loadChartData() {
            const from = document.getElementById('chart-from').value;
            const to = document.getElementById('chart-to').value;
            
            // Show loading
            document.getElementById('chart-loading').classList.remove('hidden');
            document.getElementById('chart-container').classList.add('hidden');
            
            try {
                const response = await fetch(`/charts/data?from=${from}&to=${to}&period=${currentPeriod}`);
                const result = await response.json();
                
                if (result.success) {
                    renderChart(result.data, from, to);
                    updateStats(result.data);
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
                alert('Failed to load chart data. Please try again.');
            } finally {
                document.getElementById('chart-loading').classList.add('hidden');
                document.getElementById('chart-container').classList.remove('hidden');
            }
        }

        function renderChart(data, from, to) {
            const ctx = document.getElementById('rates-chart').getContext('2d');
            
            if (chart) {
                chart.destroy();
            }
            
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: `${from}/${to}`,
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
                    devicePixelRatio: window.devicePixelRatio || 1,
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
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            ticks: {
                                maxTicksLimit: 10
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Exchange Rate'
                            }
                        }
                    }
                }
            });
        }

        function updateStats(data) {
            if (data.length === 0) {
                document.getElementById('stat-current').textContent = '-';
                document.getElementById('stat-high').textContent = '-';
                document.getElementById('stat-low').textContent = '-';
                document.getElementById('stat-avg').textContent = '-';
                return;
            }

            const rates = data.map(d => d.rate);
            const current = rates[rates.length - 1];
            const high = Math.max(...rates);
            const low = Math.min(...rates);
            const avg = rates.reduce((a, b) => a + b, 0) / rates.length;

            document.getElementById('stat-current').textContent = current.toFixed(6);
            document.getElementById('stat-high').textContent = high.toFixed(6);
            document.getElementById('stat-low').textContent = low.toFixed(6);
            document.getElementById('stat-avg').textContent = avg.toFixed(6);
        }

        // Event listeners
        document.getElementById('chart-from').addEventListener('change', loadChartData);
        document.getElementById('chart-to').addEventListener('change', loadChartData);

        // Handle window resize for responsive chart height
        window.addEventListener('resize', () => {
            if (chart) {
                const canvas = document.getElementById('rates-chart');
                const chartHeight = getChartHeight();
                canvas.style.maxHeight = chartHeight + 'px';
                chart.resize();
            }
        });

        // Load initial data
        loadChartData();
    </script>
</x-layout>

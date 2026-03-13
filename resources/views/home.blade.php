<x-layout>
    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="mb-3 inline-flex items-center rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">30+ Years of Historical Data</p>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">FX Historical Analysis Platform</h1>
                    <p class="mt-4 max-w-xl text-base text-slate-600">Explore three decades of currency exchange rate trends with interactive charts, historical API access, and fast currency conversion tools.</p>
                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <a href="/charts" class="primary-btn">Explore Charts</a>
                        <a href="#converter" class="secondary-btn">Quick Convert</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Historical Data</p>
                        <p class="mt-1 font-semibold text-slate-900">1994 – 11 Mar 2026</p>
                    </div>
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Currencies</p>
                        <p class="mt-1 font-semibold text-slate-900">50 Supported</p>
                    </div>
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Data Points</p>
                        <p class="mt-1 font-semibold text-slate-900">254K+ Rates</p>
                    </div>
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">System Status</p>
                        <p class="mt-1 font-semibold text-emerald-700">Operational</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <div class="glass-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Historical Charts</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">30+ years of data</p>
                <p class="mt-2 text-sm text-slate-600">Visualize decades of currency trends with interactive charts and analysis tools.</p>
            </div>
            <div class="glass-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Developer API</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Historical range API</p>
                <p class="mt-2 text-sm text-slate-600">Query historical exchange rate data by currency pair and date range via REST API.</p>
            </div>
            <div class="glass-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Live Conversion</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Real-time rates</p>
                <p class="mt-2 text-sm text-slate-600">Quick currency conversion using current market exchange rates.</p>
            </div>
        </section>

        @if (session('rate_limit_error'))
            <div class="rounded-xl border border-amber-300 bg-amber-50 px-6 py-5">
                <div class="flex items-start">
                    <svg class="mr-3 h-6 w-6 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="mb-1 text-sm font-semibold text-amber-800">Rate Limit Reached</h3>
                        <p class="text-sm text-amber-700">You've made too many conversion requests. Please wait about 60
                            seconds before trying again.</p>
                        <p class="mt-2 text-xs text-amber-700">⏱️ Limit: 10 conversions per minute</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-300 bg-red-50 px-6 py-4">
                <div class="flex items-start">
                    <svg class="mr-3 mt-0.5 h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800">Error</h4>
                        <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @php
            $currencies = config('currencies.supported');
            $selectedFrom = old('from_currency', $from_currency ?? 'USD');
            $selectedTo = old('to_currency', $to_currency ?? 'EUR');
        @endphp

        <section id="converter" class="rounded-3xl border border-slate-200 bg-white px-6 py-10 shadow-sm sm:px-10">
            <h2 class="text-center text-3xl font-bold text-slate-900 sm:text-4xl">Quick Currency Converter</h2>
            <p class="mt-2 text-center text-slate-600">Convert currencies using today's live exchange rates</p>

            <form action="{{ route('convert') }}" method="POST" class="mt-10">
                @csrf

                <div class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-12 lg:items-end">
                    <!-- Amount Input -->
                    <div class="lg:col-span-3">
                        <label for="amount" class="mb-2 block text-sm font-medium text-slate-600">Amount</label>
                        <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', $amount ?? '1.00') }}"
                            class="form-input w-full text-2xl font-bold text-slate-900" placeholder="1.00" required>
                        @error('amount')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- From Currency -->
                    <div class="lg:col-span-4">
                        <label class="mb-2 block text-sm font-medium text-slate-600">From</label>
                        <div class="relative">
                            <select name="from_currency" id="from_currency" class="hidden" required>
                                @foreach ($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ $selectedFrom == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="toggleDropdown('from')" class="form-input w-full cursor-pointer py-4 pl-12 pr-10 text-left text-base font-medium text-slate-700 transition hover:border-slate-400">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <img id="from-flag" src="/flags/{{ strtolower($selectedFrom) }}.svg" alt="{{ $selectedFrom }}" class="h-5 w-8 rounded object-contain">
                                </span>
                                <span id="from-selected-text" class="ml-2">{{ $selectedFrom }} - {{ $currencies[$selectedFrom] }}</span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg id="from-chevron" class="h-5 w-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                            <div id="from-dropdown" class="absolute z-10 mt-2 hidden w-full rounded-xl border border-slate-200 bg-white shadow-lg">
                                <div class="max-h-64 overflow-y-auto p-2">
                                    {{-- Popular currencies --}}
                                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Popular</div>
                                    @php
                                        $popularCodes = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR', 'SGD'];
                                    @endphp
                                    @foreach ($popularCodes as $code)
                                        @if(isset($currencies[$code]))
                                            <div onclick="selectCurrency('from', '{{ $code }}', '{{ $currencies[$code] }}')" 
                                                 class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                                <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-5 w-8 rounded object-contain">
                                                <span class="font-medium text-slate-700">{{ $code }}</span>
                                                <span class="text-slate-500">{{ $currencies[$code] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    {{-- Divider --}}
                                    <div class="my-2 border-t border-slate-100"></div>
                                    
                                    {{-- All currencies --}}
                                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">All Currencies</div>
                                    @foreach ($currencies as $code => $name)
                                        <div onclick="selectCurrency('from', '{{ $code }}', '{{ $name }}')" 
                                             class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                            <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-5 w-8 rounded object-contain">
                                            <span class="font-medium text-slate-700">{{ $code }}</span>
                                            <span class="text-slate-500">{{ $name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('from_currency')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Swap Button -->
                    <div class="flex items-center justify-center lg:col-span-1">
                        <button type="button" onclick="swapCurrencies()" class="rounded-full border border-slate-300 bg-white p-3 text-slate-600 transition hover:bg-slate-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- To Currency -->
                    <div class="lg:col-span-4">
                        <label class="mb-2 block text-sm font-medium text-slate-600">To</label>
                        <div class="relative">
                            <select name="to_currency" id="to_currency" class="hidden" required>
                                @foreach ($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ $selectedTo == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="toggleDropdown('to')" class="form-input w-full cursor-pointer py-4 pl-12 pr-10 text-left text-base font-medium text-slate-700 transition hover:border-slate-400">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <img id="to-flag" src="/flags/{{ strtolower($selectedTo) }}.svg" alt="{{ $selectedTo }}" class="h-5 w-8 rounded object-contain">
                                </span>
                                <span id="to-selected-text" class="ml-2">{{ $selectedTo }} - {{ $currencies[$selectedTo] }}</span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg id="to-chevron" class="h-5 w-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                            <div id="to-dropdown" class="absolute z-10 mt-2 hidden w-full rounded-xl border border-slate-200 bg-white shadow-lg">
                                <div class="max-h-64 overflow-y-auto p-2">
                                    {{-- Popular currencies --}}
                                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Popular</div>
                                    @php
                                        $popularCodes = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'INR', 'SGD'];
                                    @endphp
                                    @foreach ($popularCodes as $code)
                                        @if(isset($currencies[$code]))
                                            <div onclick="selectCurrency('to', '{{ $code }}', '{{ $currencies[$code] }}')" 
                                                 class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                                <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-5 w-8 rounded object-contain">
                                                <span class="font-medium text-slate-700">{{ $code }}</span>
                                                <span class="text-slate-500">{{ $currencies[$code] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    {{-- Divider --}}
                                    <div class="my-2 border-t border-slate-100"></div>
                                    
                                    {{-- All currencies --}}
                                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-slate-400">All Currencies</div>
                                    @foreach ($currencies as $code => $name)
                                        <div onclick="selectCurrency('to', '{{ $code }}', '{{ $name }}')" 
                                             class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                            <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-5 w-8 rounded object-contain">
                                            <span class="font-medium text-slate-700">{{ $code }}</span>
                                            <span class="text-slate-500">{{ $name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('to_currency')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Promotional Banner + Convert Button -->
                <div class="mx-auto mt-8 grid max-w-5xl items-center gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 px-6 py-4">
                        <p class="font-semibold text-slate-800">Rates sourced from ExchangeRate-API</p>
                        <p class="text-sm text-slate-600">Live market rates updated every 24 hours</p>
                    </div>

                    <button type="submit" class="rounded-2xl bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition hover:bg-blue-700">
                        Convert
                    </button>
                </div>
            </form>

            <script>
                function toggleDropdown(type) {
                    const dropdown = document.getElementById(`${type}-dropdown`);
                    const chevron = document.getElementById(`${type}-chevron`);
                    const otherDropdown = document.getElementById(type === 'from' ? 'to-dropdown' : 'from-dropdown');
                    const otherChevron = document.getElementById(type === 'from' ? 'to-chevron' : 'from-chevron');
                    
                    // Close other dropdown
                    otherDropdown.classList.add('hidden');
                    otherChevron.classList.remove('rotate-180');
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('hidden');
                    chevron.classList.toggle('rotate-180');
                }
                
                function selectCurrency(type, code, name) {
                    // Update hidden select
                    document.getElementById(`${type}_currency`).value = code;
                    
                    // Update visible button text and flag
                    document.getElementById(`${type}-selected-text`).textContent = `${code} - ${name}`;
                    document.getElementById(`${type}-flag`).src = `/flags/${code.toLowerCase()}.svg`;
                    document.getElementById(`${type}-flag`).alt = code;
                    
                    // Close dropdown
                    document.getElementById(`${type}-dropdown`).classList.add('hidden');
                    document.getElementById(`${type}-chevron`).classList.remove('rotate-180');
                }
                
                function swapCurrencies() {
                    const fromSelect = document.getElementById('from_currency');
                    const toSelect = document.getElementById('to_currency');
                    
                    const tempValue = fromSelect.value;
                    const tempText = document.getElementById('from-selected-text').textContent;
                    const tempFlagSrc = document.getElementById('from-flag').src;
                    const tempFlagAlt = document.getElementById('from-flag').alt;
                    
                    // Swap values
                    fromSelect.value = toSelect.value;
                    document.getElementById('from-selected-text').textContent = document.getElementById('to-selected-text').textContent;
                    document.getElementById('from-flag').src = document.getElementById('to-flag').src;
                    document.getElementById('from-flag').alt = document.getElementById('to-flag').alt;
                    
                    toSelect.value = tempValue;
                    document.getElementById('to-selected-text').textContent = tempText;
                    document.getElementById('to-flag').src = tempFlagSrc;
                    document.getElementById('to-flag').alt = tempFlagAlt;
                }
                
                // Close dropdowns when clicking outside
                document.addEventListener('click', function(event) {
                    const fromDropdown = document.getElementById('from-dropdown');
                    const toDropdown = document.getElementById('to-dropdown');
                    const fromChevron = document.getElementById('from-chevron');
                    const toChevron = document.getElementById('to-chevron');
                    
                    if (!event.target.closest('[onclick*="toggleDropdown"]') && 
                        !event.target.closest('#from-dropdown') && 
                        !event.target.closest('#to-dropdown')) {
                        fromDropdown.classList.add('hidden');
                        toDropdown.classList.add('hidden');
                        fromChevron.classList.remove('rotate-180');
                        toChevron.classList.remove('rotate-180');
                    }
                });
            </script>
        </section>

        <section class="grid gap-6 lg:grid-cols-5">
            <!-- Popular Currency Pairs -->
            <div class="glass-panel p-7 lg:col-span-3 sm:p-8">
                <h3 class="text-lg font-semibold text-slate-900">Popular Currency Pairs</h3>
                <p class="mt-1 text-sm text-slate-600">Jump straight into a chart for the most traded pairs.</p>

                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @php
                        $pairs = [
                            ['from' => 'GBP', 'to' => 'USD', 'label' => 'GBP / USD'],
                            ['from' => 'EUR', 'to' => 'USD', 'label' => 'EUR / USD'],
                            ['from' => 'GBP', 'to' => 'EUR', 'label' => 'GBP / EUR'],
                            ['from' => 'USD', 'to' => 'JPY', 'label' => 'USD / JPY'],
                            ['from' => 'GBP', 'to' => 'JPY', 'label' => 'GBP / JPY'],
                            ['from' => 'USD', 'to' => 'CHF', 'label' => 'USD / CHF'],
                            ['from' => 'EUR', 'to' => 'GBP', 'label' => 'EUR / GBP'],
                            ['from' => 'AUD', 'to' => 'USD', 'label' => 'AUD / USD'],
                            ['from' => 'USD', 'to' => 'CAD', 'label' => 'USD / CAD'],
                        ];
                    @endphp
                    @foreach ($pairs as $pair)
                        <a href="/charts?from={{ $pair['from'] }}&to={{ $pair['to'] }}"
                           class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                            <div class="flex -space-x-1.5 shrink-0">
                                <img src="/flags/{{ strtolower($pair['from']) }}.svg" alt="{{ $pair['from'] }}" class="h-4 w-6 rounded-sm object-contain ring-1 ring-white">
                                <img src="/flags/{{ strtolower($pair['to']) }}.svg" alt="{{ $pair['to'] }}" class="h-4 w-6 rounded-sm object-contain ring-1 ring-white">
                            </div>
                            <span>{{ $pair['label'] }}</span>
                            <svg class="ml-auto h-3.5 w-3.5 text-slate-400 transition group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Data Coverage -->
            <div class="min-w-0 space-y-6 lg:col-span-2">
                <div class="glass-panel p-7">
                    <h3 class="text-lg font-semibold text-slate-900">Data Coverage</h3>
                    <p class="mt-1 text-sm text-slate-600">What's included in the historical dataset.</p>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Date Range</span>
                            <span class="text-sm font-semibold text-slate-900">1994 – 11 Mar 2026</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Snapshots</span>
                            <span class="text-sm font-semibold text-slate-900">8,179 daily</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Exchange Rates</span>
                            <span class="text-sm font-semibold text-slate-900">253,969 total</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Base Currency</span>
                            <span class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <img src="/flags/usd.svg" alt="USD" class="h-3.5 w-5 rounded-sm object-contain">
                                USD
                            </span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="text-sm text-slate-600">Historical API</span>
                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Available</span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg border-l-4 border-amber-500 bg-amber-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-900">Data Availability Note</p>
                        <p class="mt-2 text-sm text-amber-800">The dataset does <strong>not include every single day</strong>. Data is available for business days only (Monday–Friday), excluding weekends and public holidays. Historical gaps may exist for certain date ranges or currencies depending on when data was available from our sources.</p>
                    </div>
                </div>

                <a href="/charts" class="glass-panel flex items-center gap-4 p-6 transition hover:border-blue-200 hover:bg-blue-50/50 group">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 group-hover:bg-blue-200 transition">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 group-hover:text-blue-700 transition">Explore Historical Charts</p>
                        <p class="mt-0.5 text-sm text-slate-500">Interactive charts powered by 30+ years of USD-based historical data (1994 – 11 Mar 2026)</p>
                    </div>
                    <svg class="ml-auto h-5 w-5 text-slate-400 group-hover:text-blue-500 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </section>
    </div>
</x-layout>

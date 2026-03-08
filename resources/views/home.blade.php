<x-layout>
    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="mb-3 inline-flex items-center rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Modern FX Platform</p>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">FX Tracker</h1>
                    <p class="mt-4 max-w-xl text-base text-slate-600">A modern, developer-friendly conversion workspace for teams that need speed, accuracy, and clean API access.</p>
                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <a href="#converter" class="primary-btn">Start converting</a>
                        <a href="#developer" class="secondary-btn">Developer API</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Platform</p>
                        <p class="mt-1 font-semibold text-slate-900">Infinite Finances</p>
                    </div>
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Product</p>
                        <p class="mt-1 font-semibold text-slate-900">FX Tracker</p>
                    </div>
                    <div class="glass-panel p-4">
                        <p class="text-slate-500">Rate Source</p>
                        <p class="mt-1 font-semibold text-slate-900">ExchangeRate API</p>
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
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Live Conversion</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Real-time rates</p>
                <p class="mt-2 text-sm text-slate-600">Always calculate with fresh market data from the configured provider.</p>
            </div>
            <div class="glass-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Developer First</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Simple API access</p>
                <p class="mt-2 text-sm text-slate-600">Generate API keys and integrate conversion endpoints quickly.</p>
            </div>
            <div class="glass-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Reliable UX</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Rate-limited safety</p>
                <p class="mt-2 text-sm text-slate-600">Built-in throttling protects platform stability and performance.</p>
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
            <h2 class="text-center text-3xl font-bold text-slate-900 sm:text-4xl">Global currency conversions</h2>

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
                                    <img id="from-flag" src="/flags/{{ strtolower($selectedFrom) }}.svg" alt="{{ $selectedFrom }}" class="h-6 w-6 rounded object-cover">
                                </span>
                                <span id="from-selected-text">{{ $selectedFrom }} - {{ $currencies[$selectedFrom] }}</span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg id="from-chevron" class="h-5 w-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                            <div id="from-dropdown" class="absolute z-10 mt-2 hidden w-full rounded-xl border border-slate-200 bg-white shadow-lg">
                                <div class="max-h-64 overflow-y-auto p-2">
                                    @foreach ($currencies as $code => $name)
                                        <div onclick="selectCurrency('from', '{{ $code }}', '{{ $name }}')" 
                                             class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                            <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-6 w-6 rounded object-cover">
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
                                    <img id="to-flag" src="/flags/{{ strtolower($selectedTo) }}.svg" alt="{{ $selectedTo }}" class="h-6 w-6 rounded object-cover">
                                </span>
                                <span id="to-selected-text">{{ $selectedTo }} - {{ $currencies[$selectedTo] }}</span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg id="to-chevron" class="h-5 w-5 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>
                            <div id="to-dropdown" class="absolute z-10 mt-2 hidden w-full rounded-xl border border-slate-200 bg-white shadow-lg">
                                <div class="max-h-64 overflow-y-auto p-2">
                                    @foreach ($currencies as $code => $name)
                                        <div onclick="selectCurrency('to', '{{ $code }}', '{{ $name }}')" 
                                             class="flex cursor-pointer items-center gap-3 rounded-lg px-4 py-3 text-sm transition hover:bg-slate-50">
                                            <img src="/flags/{{ strtolower($code) }}.svg" alt="{{ $code }}" class="h-6 w-6 rounded object-cover">
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
                        <p class="font-semibold text-slate-800">Looking to make large transfers?</p>
                        <p class="text-sm text-slate-600">We can beat competitor rates</p>
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
            <div class="glass-panel hidden p-7 lg:col-span-3 sm:p-8">
                <!-- Hidden - keeping for structure but not displaying -->
            </div>

            <div class="min-w-0 space-y-6 lg:col-span-2">
                <div class="glass-panel p-7">
                    <h3 class="text-lg font-semibold text-slate-900">Live Result</h3>
                    <p class="mt-1 text-sm text-slate-600">Current conversion output and quoted rate.</p>

                    <div class="mt-6 space-y-5">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">From</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ isset($amount) ? number_format($amount, 2) : '0.00' }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $from_currency ?? 'USD' }}</p>
                        </div>

                        <div class="flex items-center justify-center text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </div>

                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">To</p>
                            <p class="mt-1 text-2xl font-semibold text-blue-700">{{ isset($converted_amount) ? number_format($converted_amount, 2) : '0.00' }}</p>
                            <p class="mt-1 text-sm text-blue-700/80">{{ $to_currency ?? 'EUR' }}</p>
                        </div>
                    </div>

                    @if (isset($rate, $from_currency, $to_currency))
                        <div class="mt-6 rounded-xl border border-slate-200 bg-white p-4 text-sm">
                            <span class="font-semibold text-slate-700">Exchange Rate:</span>
                            <span class="font-mono text-slate-900"> 1 {{ $from_currency }} = {{ number_format($rate, 4) }} {{ $to_currency }}</span>
                        </div>
                    @endif
                </div>

                <div id="developer" class="glass-panel min-w-0 p-7">
                    <h3 class="text-lg font-semibold text-slate-900">Developer Friendly</h3>
                    <p class="mt-1 text-sm text-slate-600">Use your API key to integrate conversion into apps and internal tools.</p>

                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Endpoint</p>
                        <div class="overflow-x-auto">
                            <p class="w-max min-w-full whitespace-nowrap font-mono text-sm text-slate-900">GET {{ config('app.url') }}/api/v1/convert</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-900 p-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Example Request</p>
                        <pre class="max-w-full overflow-x-auto whitespace-nowrap text-xs leading-6 text-emerald-300">curl -X GET "{{ config('app.url') }}/api/v1/convert?amount=100&from=GBP&to=USD" \
  -H "Authorization: Bearer YOUR_API_KEY"</pre>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>

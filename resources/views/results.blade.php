<x-layout>
    <div class="w-full space-y-10">
        <!-- Back to converter -->
        <div class="flex items-center justify-between">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to home
            </a>
            <a href="/charts" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                View Historical Charts
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7"></path>
                </svg>
            </a>
        </div>

        @php
            $currencies = config('currencies.supported');
        @endphp

        <!-- Converter Section -->
        <section class="rounded-3xl border border-slate-200 bg-white px-6 py-10 shadow-sm sm:px-10">
            <h2 class="text-center text-3xl font-bold text-slate-900 sm:text-4xl">Convert Another Amount</h2>
            <p class="mt-2 text-center text-slate-600">Quick currency conversion using live rates</p>

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
                                    <option value="{{ $code }}" {{ $from_currency == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="toggleDropdown('from')" class="form-input w-full cursor-pointer py-4 pl-12 pr-10 text-left text-base font-medium text-slate-700 transition hover:border-slate-400">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <img id="from-flag" src="/flags/{{ strtolower($from_currency) }}.svg" alt="{{ $from_currency }}" class="h-5 w-8 rounded object-contain">
                                </span>
                                <span id="from-selected-text" class="ml-2">{{ $from_currency }} - {{ $currencies[$from_currency] }}</span>
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
                                    <option value="{{ $code }}" {{ $to_currency == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="toggleDropdown('to')" class="form-input w-full cursor-pointer py-4 pl-12 pr-10 text-left text-base font-medium text-slate-700 transition hover:border-slate-400">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                    <img id="to-flag" src="/flags/{{ strtolower($to_currency) }}.svg" alt="{{ $to_currency }}" class="h-5 w-8 rounded object-contain">
                                </span>
                                <span id="to-selected-text" class="ml-2">{{ $to_currency }} - {{ $currencies[$to_currency] }}</span>
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

                <!-- Convert Button -->
                <div class="mx-auto mt-8 max-w-5xl">
                    <button type="submit" class="w-full rounded-2xl bg-blue-600 px-8 py-4 text-lg font-semibold text-white transition hover:bg-blue-700">
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

        <!-- Results Section -->
        <section id="results" class="rounded-3xl border border-emerald-100 bg-linear-to-r from-emerald-50 to-white px-8 py-12 sm:px-12">
            <div class="mb-6 text-center">
                <p class="mb-3 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Conversion Complete</p>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Your Results</h1>
            </div>

            <!-- Main Conversion Display -->
            <div class="mx-auto max-w-3xl">
                <div class="space-y-6">
                    <!-- From Amount -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-8">
                        <p class="text-sm font-medium text-slate-600">You send</p>
                        <div class="mt-3 flex items-center gap-4">
                            <img src="/flags/{{ strtolower($from_currency) }}.svg" alt="{{ $from_currency }}" class="h-10 w-10 rounded object-cover">
                            <div>
                                <p class="text-4xl font-bold text-slate-900">{{ number_format($amount, 2) }}</p>
                                <p class="mt-1 text-lg font-medium text-slate-600">{{ $from_currency }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="flex justify-center">
                        <div class="rounded-full bg-blue-100 p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- To Amount -->
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-8">
                        <p class="text-sm font-medium text-blue-700">They receive</p>
                        <div class="mt-3 flex items-center gap-4">
                            <img src="/flags/{{ strtolower($to_currency) }}.svg" alt="{{ $to_currency }}" class="h-10 w-10 rounded object-cover">
                            <div>
                                <p class="text-4xl font-bold text-blue-900">{{ number_format($converted_amount, 2) }}</p>
                                <p class="mt-1 text-lg font-medium text-blue-700">{{ $to_currency }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Exchange Rate Info -->
                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-600">Exchange Rate</p>
                                <p class="mt-1 font-mono text-lg font-semibold text-slate-900">
                                    1 {{ $from_currency }} = {{ number_format($rate, 4) }} {{ $to_currency }}
                                </p>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1">
                                <p class="text-xs font-semibold text-emerald-700">Live Rate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    @auth
                        <a href="/profile" class="rounded-2xl border border-slate-300 bg-white px-8 py-4 text-center text-lg font-semibold text-slate-700 transition hover:bg-slate-50">
                            View History
                        </a>
                    @else
                        <a href="/register" class="rounded-2xl border border-slate-300 bg-white px-8 py-4 text-center text-lg font-semibold text-slate-700 transition hover:bg-slate-50">
                            Create Account to Save
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Additional Info -->
        <section class="grid gap-6 md:grid-cols-3">
            <div class="glass-panel p-6">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Real-time Rates</h3>
                <p class="mt-2 text-sm text-slate-600">Exchange rates updated in real-time from our provider.</p>
            </div>

            <div class="glass-panel p-6">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Accurate Conversions</h3>
                <p class="mt-2 text-sm text-slate-600">Reliable calculations for all your currency needs.</p>
            </div>

            <div class="glass-panel p-6">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Lightning Fast</h3>
                <p class="mt-2 text-sm text-slate-600">Get conversion results instantly with our optimized system.</p>
            </div>
        </section>
    </div>
    
    <script>
        // Auto-scroll to results if they exist
        @if(isset($converted_amount))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    document.getElementById('results').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            });
        @endif
    </script>
</x-layout>

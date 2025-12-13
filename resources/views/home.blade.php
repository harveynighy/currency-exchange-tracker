<x-layout>
    <div class="w-full">
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-600 px-6 py-4 mb-6 rounded-r">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800">Error</h4>
                        <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Currency Conversion</h2>
                <p class="text-sm text-gray-500 mt-1">Enter amount and select currencies to convert</p>
            </div>
            
            <form action="{{ route('convert') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount" 
                        step="0.01"
                        value="{{ old('amount', $amount ?? '') }}"
                        class="w-full px-4 py-3 text-lg border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors"
                        placeholder="0.00"
                        required
                    >
                    @error('amount')
                        <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="from_currency" class="block text-sm font-semibold text-gray-700 mb-2">From Currency</label>
                        <input 
                            type="text" 
                            name="from_currency" 
                            id="from_currency" 
                            list="currency-list"
                            maxlength="3"
                            value="{{ old('from_currency', $from_currency ?? 'USD') }}"
                            class="w-full px-4 py-3 text-lg font-semibold border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors uppercase"
                            placeholder="USD"
                            required
                        >
                        @error('from_currency')
                            <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="to_currency" class="block text-sm font-semibold text-gray-700 mb-2">To Currency</label>
                        <input 
                            type="text" 
                            name="to_currency" 
                            id="to_currency" 
                            list="currency-list"
                            maxlength="3"
                            value="{{ old('to_currency', $to_currency ?? 'EUR') }}"
                            class="w-full px-4 py-3 text-lg font-semibold border border-gray-300 rounded focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors uppercase"
                            placeholder="EUR"
                            required
                        >
                        @error('to_currency')
                            <p class="text-red-600 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <datalist id="currency-list">
                    <option value="USD">US Dollar</option>
                    <option value="EUR">Euro</option>
                    <option value="GBP">British Pound</option>
                    <option value="JPY">Japanese Yen</option>
                    <option value="AUD">Australian Dollar</option>
                    <option value="CAD">Canadian Dollar</option>
                    <option value="CHF">Swiss Franc</option>
                    <option value="CNY">Chinese Yuan</option>
                    <option value="INR">Indian Rupee</option>
                    <option value="MXN">Mexican Peso</option>
                    <option value="BRL">Brazilian Real</option>
                    <option value="ZAR">South African Rand</option>
                    <option value="NZD">New Zealand Dollar</option>
                    <option value="SGD">Singapore Dollar</option>
                    <option value="HKD">Hong Kong Dollar</option>
                    <option value="NOK">Norwegian Krone</option>
                    <option value="SEK">Swedish Krona</option>
                    <option value="DKK">Danish Krone</option>
                    <option value="PLN">Polish Zloty</option>
                    <option value="THB">Thai Baht</option>
                    <option value="IDR">Indonesian Rupiah</option>
                    <option value="MYR">Malaysian Ringgit</option>
                    <option value="PHP">Philippine Peso</option>
                    <option value="KRW">South Korean Won</option>
                    <option value="TRY">Turkish Lira</option>
                    <option value="RUB">Russian Ruble</option>
                    <option value="AED">UAE Dirham</option>
                    <option value="SAR">Saudi Riyal</option>
                    <option value="EGP">Egyptian Pound</option>
                    <option value="NGN">Nigerian Naira</option>
                </datalist>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                >
                    Calculate Conversion
                </button>
            </form>
        </div>

        @if(isset($converted_amount))
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Conversion Result</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="text-center md:text-left">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">From</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($amount, 2) }}
                        </p>
                        <p class="text-sm font-semibold text-gray-600 mt-1">{{ $from_currency }}</p>
                    </div>
                    
                    <div class="flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">To</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ number_format($converted_amount, 2) }}
                        </p>
                        <p class="text-sm font-semibold text-gray-600 mt-1">{{ $to_currency }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded border border-gray-200 p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-gray-700">Exchange Rate</span>
                        <span class="font-mono text-gray-900">
                            1 {{ $from_currency }} = {{ number_format($rate, 4) }} {{ $to_currency }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>

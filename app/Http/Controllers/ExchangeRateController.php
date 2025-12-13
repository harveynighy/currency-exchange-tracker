<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    protected $exchangeService;

    public function __construct(ExchangeRateService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    public function index()
    {
        // Just show the home page with the form
        return view('home');
    }

    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
        ]);

        try {
            $amount = $request->amount;
            $fromCurrency = strtoupper($request->from_currency);
            $toCurrency = strtoupper($request->to_currency);

            // Get rates for the base currency
            $ratesData = $this->exchangeService->getRates($fromCurrency);

            if (isset($ratesData['conversion_rates'][$toCurrency])) {
                $rate = $ratesData['conversion_rates'][$toCurrency];
                $convertedAmount = $amount * $rate;

                return view('home', [
                    'amount' => $amount,
                    'from_currency' => $fromCurrency,
                    'to_currency' => $toCurrency,
                    'rate' => $rate,
                    'converted_amount' => $convertedAmount,
                ]);
            }

            return back()->with('error', 'Currency not found');
        } catch (\Exception $e) {
            // only redirect to home for rate limiting errors
            if (str_contains($e->getMessage(), 'Too many requests')) {
                return redirect()->route('home')
                    ->with('rate_limit_error', true)
                    ->withInput();
            }

            // all other errors just go back
            return back()->with('error', 'Unable to fetch exchange rates: ' . $e->getMessage());
        }
    }
}

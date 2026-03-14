<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoneyPageController extends Controller
{
    public function index()
    {
        return view('money-pages.index', [
            'currencies' => config('currencies.supported', []),
            'pairs' => $this->topPairs(),
        ]);
    }

    public function show(string $from, string $to)
    {
        $currencies = config('currencies.supported', []);

        $from = strtoupper($from);
        $to = strtoupper($to);

        if (!isset($currencies[$from]) || !isset($currencies[$to]) || $from === $to) {
            abort(404);
        }

        $related = collect($this->topPairs())
            ->filter(fn ($pair) => ($pair['from'] === $from || $pair['to'] === $to) && !($pair['from'] === $from && $pair['to'] === $to))
            ->take(6)
            ->values()
            ->all();

        return view('money-pages.show', [
            'from' => $from,
            'to' => $to,
            'fromName' => $currencies[$from],
            'toName' => $currencies[$to],
            'relatedPairs' => $related,
        ]);
    }

    public function topPairs(): array
    {
        return [
            ['from' => 'USD', 'to' => 'EUR'],
            ['from' => 'EUR', 'to' => 'USD'],
            ['from' => 'GBP', 'to' => 'USD'],
            ['from' => 'USD', 'to' => 'GBP'],
            ['from' => 'USD', 'to' => 'JPY'],
            ['from' => 'EUR', 'to' => 'GBP'],
            ['from' => 'USD', 'to' => 'CAD'],
            ['from' => 'USD', 'to' => 'AUD'],
            ['from' => 'USD', 'to' => 'CHF'],
            ['from' => 'USD', 'to' => 'INR'],
            ['from' => 'USD', 'to' => 'CNY'],
            ['from' => 'AUD', 'to' => 'USD'],
        ];
    }
}

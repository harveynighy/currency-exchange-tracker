@php
    $markdown = <<<'MD'
The Wall Street Crash of 1929 was more than an equity-market collapse. It became a monetary turning point that reshaped how investors and policymakers viewed the U.S. dollar (USD) for years.

## 1) Why the crash was so disruptive

In the late 1920s, stock valuations were stretched, leverage was high, and confidence was extreme. The sharp sell-offs across Black Thursday, Black Monday, and Black Tuesday triggered a deep collapse in sentiment and credit.

The crash did not alone create the Great Depression, but it accelerated the broader breakdown in lending, trade, and output.

## 2) USD under deflation pressure

The U.S. was tied to a gold-based monetary regime. As the economy weakened:

- Credit contracted
- Bank failures increased
- Prices fell (deflation)

Deflation raised the real burden of debt. Even though each dollar bought more goods, economic activity weakened because households and businesses delayed spending and investment.

## 3) Safety demand vs policy limits

In crisis periods, capital often seeks depth and liquidity. The U.S. had structural scale, so USD could still attract confidence. But policy flexibility was constrained under the gold framework, limiting the speed of monetary response.

That tension defined the early 1930s: high fear, fragile growth, and constrained stabilization tools.

## 4) The policy regime shift (1933–1934)

A major break came when gold convertibility changed and the dollar-gold relationship was reset. This effectively devalued USD versus gold and gave policymakers more room to address deflation.

Key effects:

- More monetary-policy flexibility
- Better ability to support prices and activity
- A step away from rigid pre-crash constraints

## 5) Why this still matters for FX readers

The Wall Street Crash shows that currencies are driven by both **risk sentiment** and **policy regime changes**.

Main lesson: a shock may first strengthen safe-haven demand dynamics, then later reverse valuation paths once policy architecture changes.
MD;
@endphp

{!! \Illuminate\Support\Str::markdown($markdown, [
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]) !!}

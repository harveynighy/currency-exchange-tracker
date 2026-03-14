@php
    $markdown = <<<'MD'
The 2016 Brexit referendum was one of the biggest political shocks for a major currency in modern markets. For GBP, it marked a structural repricing event rather than a short-lived headline move.

## 1) Why the vote mattered for sterling

Before the referendum, many market participants positioned for a "Remain" result. The "Leave" outcome forced an immediate repricing of UK growth, trade assumptions, and risk premium.

In FX terms, this was a high-magnitude uncertainty shock.

## 2) The immediate GBP move

Sterling dropped sharply, especially versus USD and EUR, in one of the largest modern single-session moves for GBP/USD.

What drove the drop:

- Growth outlook downgrade
- Trade-access uncertainty
- Expectations of easier Bank of England policy
- Broad risk-off positioning

## 3) Why pressure lasted beyond the first week

Brexit unfolded through years of negotiations and political milestones. Each deadline, vote, and legal shift created new uncertainty around:

- Goods and services trade terms
- Investment confidence
- Financial-services access
- UK medium-term productivity assumptions

This kept GBP highly headline-sensitive.

## 4) Policy and valuation dynamics

The Bank of England responded with accommodative policy to support domestic conditions. That helped stability but also affected rate-differential support for GBP.

Sterling’s path reflected two opposing forces:

1. **Risk premium pressure** from political uncertainty
2. **Relief rebounds** when outcomes looked less disruptive than worst-case expectations

## 5) Practical FX takeaway

Brexit is a strong reminder that currencies move on expectations, regime changes, and policy response—not only current data prints.

For GBP analysis, combining event timelines with historical rate charts gives a far clearer picture than a single spot-rate snapshot.
MD;
@endphp

{!! \Illuminate\Support\Str::markdown($markdown, [
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]) !!}

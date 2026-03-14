<x-layout
    title="Currency Market Blog & Analysis | FX Tracker"
    description="Read currency market analysis and historical event breakdowns. Filter blog posts by currency to find focused FX insights."
    keywords="currency blog, forex analysis, exchange rate news, historical currency events, FX insights"
>
    <div class="w-full space-y-10">
        <section class="rounded-3xl border border-blue-100 bg-linear-to-r from-blue-50 to-white px-8 py-12 sm:px-12">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mb-3 inline-flex rounded-full border border-blue-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-blue-700">Analysis & Commentary</p>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Currency Market Blog</h1>
                <p class="mt-4 text-lg text-slate-600">Explore how economic and historical events affected exchange rates across major currencies.</p>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <form action="{{ route('blog.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <label for="currency" class="text-sm font-medium text-slate-700">Filter by currency</label>
                    <select id="currency" name="currency" class="form-input min-w-44">
                        <option value="">All currencies</option>
                        @foreach ($availableCurrencies as $code => $name)
                            <option value="{{ $code }}" {{ $selectedCurrency === $code ? 'selected' : '' }}>{{ $code }} - {{ $name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="secondary-btn">Apply</button>
                    @if ($selectedCurrency)
                        <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Clear</a>
                    @endif
                </form>

            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        {{ $post->published_at->format('d M Y') }}
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-700">{{ $post->title }}</a>
                    </h2>
                    <p class="mt-3 text-sm text-slate-600">{{ $post->excerpt }}</p>

                    @if (!empty($post->currency_tags))
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($post->currency_tags as $tag)
                                <a href="{{ route('blog.index', ['currency' => $tag]) }}"
                                    class="rounded-full border border-slate-300 px-2.5 py-1 text-xs font-medium text-slate-700 hover:border-blue-400 hover:text-blue-700">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-600 md:col-span-2 lg:col-span-3">
                    No posts found for this currency filter yet.
                </div>
            @endforelse
        </section>

    </div>
</x-layout>

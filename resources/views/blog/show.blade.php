<x-layout
    :title="$post->title . ' | FX Tracker Blog'"
    :description="$post->excerpt ?: 'Currency analysis from FX Tracker.'"
    :keywords="implode(', ', array_map(fn($tag) => strtolower($tag) . ' exchange rate', $post->currency_tags ?? []))"
>
    @php
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->excerpt ?: 'Currency analysis from FX Tracker.',
            'datePublished' => $post->published_at->toAtomString(),
            'dateModified' => $post->updated_at->toAtomString(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author ?? 'FX Tracker',
            ],
            'keywords' => implode(', ', $post->currency_tags ?? []),
        ];
    @endphp

    <article class="mx-auto max-w-4xl space-y-8">
        <header class="rounded-3xl border border-slate-200 bg-white px-8 py-10 shadow-sm">
            <a href="{{ route('blog.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">← Back to blog</a>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-slate-500">
                Published {{ $post->published_at->format('d M Y') }} · by {{ $post->author ?? 'FX Tracker' }}
            </p>

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

        </header>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            @if ($post->excerpt)
                <p class="mb-6 border-l-4 border-blue-500 pl-4 text-lg text-slate-700">{{ $post->excerpt }}</p>
            @endif

            <div class="markdown-content max-w-none">
                @includeIf($post->view)
            </div>
        </section>

        @if ($relatedPosts->isNotEmpty())
            <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-900">Related posts</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach ($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="rounded-xl border border-slate-200 p-4 hover:border-blue-300">
                            <p class="text-sm font-semibold text-slate-900">{{ $related->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $related->published_at->format('d M Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>

    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-layout>

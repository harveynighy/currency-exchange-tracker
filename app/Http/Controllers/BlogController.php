<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $supportedCurrencies = config('currencies.supported', []);
        $selectedCurrency = strtoupper((string) $request->query('currency', ''));

        $posts = $this->posts()
            ->when($selectedCurrency !== '' && isset($supportedCurrencies[$selectedCurrency]), function (Collection $collection) use ($selectedCurrency) {
                return $collection->filter(fn ($post) => in_array($selectedCurrency, $post->currency_tags, true));
            })
            ->sortByDesc('published_at')
            ->values();

        $taggedCurrencies = $this->posts()
            ->pluck('currency_tags')
            ->flatten()
            ->filter(fn ($tag) => is_string($tag) && isset($supportedCurrencies[$tag]))
            ->unique()
            ->sort()
            ->values();

        $availableCurrencies = $taggedCurrencies
            ->mapWithKeys(fn ($code) => [$code => $supportedCurrencies[$code]])
            ->all();

        return view('blog.index', [
            'posts' => $posts,
            'selectedCurrency' => $selectedCurrency,
            'availableCurrencies' => $availableCurrencies,
        ]);
    }

    public function show(string $slug)
    {
        $post = $this->posts()->firstWhere('slug', $slug);
        abort_if(!$post, 404);

        $relatedPosts = $this->posts()
            ->filter(fn ($candidate) => $candidate->slug !== $post->slug)
            ->filter(function ($candidate) use ($post) {
                if (empty($post->currency_tags)) {
                    return false;
                }

                return count(array_intersect($post->currency_tags, $candidate->currency_tags)) > 0;
            })
            ->sortByDesc('published_at')
            ->take(4)
            ->values();

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    protected function posts(): Collection
    {
        return collect(config('blog.posts', []))
            ->map(function (array $post) {
                $publishedAt = isset($post['published_at']) ? Carbon::parse($post['published_at']) : now();
                $view = $post['view'] ?? null;

                return (object) [
                    'title' => $post['title'],
                    'slug' => $post['slug'],
                    'excerpt' => $post['excerpt'] ?? null,
                    'currency_tags' => $post['currency_tags'] ?? [],
                    'published_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                    'author' => $post['author'] ?? 'FX Tracker',
                    'view' => $view,
                    'is_published' => (bool) ($post['is_published'] ?? true),
                ];
            })
            ->filter(fn ($post) => $post->is_published && $post->published_at->lte(now()) && !empty($post->view) && view()->exists($post->view))
            ->values();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BlogBuilderPost;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap conforming to Sitemaps.org 0.9 schema.
     */
    public function index(): Response
    {
        $urls = [];
        $addedLocations = [];
        $nowIso = now()->toIso8601String();

        // 1. Core Platform & Static Pages
        $staticRoutes = [
            ['route' => 'home', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => 'about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['route' => 'blog.index', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'privacy', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['route' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'terms', 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $item) {
            if (Route::has($item['route'])) {
                $loc = $this->resolveCanonicalUrl(route($item['route']));
                if (!isset($addedLocations[$loc])) {
                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $nowIso,
                        'changefreq' => $item['changefreq'],
                        'priority' => $item['priority'],
                    ];
                    $addedLocations[$loc] = true;
                }
            }
        }

        // 2. PDF Tools Landing & Individual PDF Utility Pages
        $pdfRoutes = [
            'pdf.landing',
            'pdf.tools',
            'pdf.merge',
            'pdf.split',
            'pdf.compress',
            'pdf.to-word',
            'word.to-pdf',
            'image.to-pdf',
            'pdf.ocr',
            'pdf.watermark',
        ];

        foreach ($pdfRoutes as $pdfRoute) {
            if (Route::has($pdfRoute)) {
                $loc = $this->resolveCanonicalUrl(route($pdfRoute));
                if (!isset($addedLocations[$loc])) {
                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $nowIso,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                    $addedLocations[$loc] = true;
                }
            }
        }

        // 3. Dynamic Tool Registry Pages (from config/tools.php)
        $tools = config('tools', []);
        foreach ($tools as $tool) {
            $routeName = $tool['route_name'] ?? null;
            if ($routeName && Route::has($routeName)) {
                $loc = $this->resolveCanonicalUrl(route($routeName));
                if (!isset($addedLocations[$loc])) {
                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $nowIso,
                        'changefreq' => 'weekly',
                        'priority' => '0.9',
                    ];
                    $addedLocations[$loc] = true;
                }
            }
        }

        // 4. Published Blog Posts (Dynamic from database)
        try {
            $posts = BlogBuilderPost::where('status', 'published')
                ->whereNull('deleted_at')
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->where(function ($q) {
                    $q->whereNull('published_at')->orWhere('published_at', '<=', now());
                })
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->select(['id', 'slug', 'canonical_url', 'published_at', 'updated_at', 'created_at'])
                ->get();

            foreach ($posts as $post) {
                // Canonical URL logic matching show.blade.php: $post->canonical_url ?: route('blog.show', $post->slug)
                $canonical = !empty($post->canonical_url)
                    ? $post->canonical_url
                    : route('blog.show', $post->slug);

                $loc = $this->resolveCanonicalUrl($canonical);

                if (!isset($addedLocations[$loc])) {
                    $timestamp = $post->updated_at ?? $post->published_at ?? $post->created_at ?? now();

                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $timestamp->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                    $addedLocations[$loc] = true;
                }
            }
        } catch (\Throwable $e) {
            Log::error('Sitemap blog post retrieval error: ' . $e->getMessage());
        }

        return response()->view('sitemap', compact('urls'), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Resolve canonical URL using the canonical domain in production.
     */
    protected function resolveCanonicalUrl(string $url): string
    {
        // In production, ensure absolute URL begins with canonical domain https://cypherlab.in
        if (app()->environment('production') || str_contains((string) config('app.url'), 'cypherlab.in')) {
            $parsed = parse_url($url);
            $path = $parsed['path'] ?? '/';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
            return 'https://cypherlab.in' . $path . $query;
        }

        return $url;
    }
}

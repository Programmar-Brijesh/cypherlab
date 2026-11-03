<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Models\BlogBuilderPost;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];
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
                $urls[] = [
                    'loc' => route($item['route']),
                    'lastmod' => $nowIso,
                    'changefreq' => $item['changefreq'],
                    'priority' => $item['priority'],
                ];
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
                $loc = route($pdfRoute);
                // Prevent duplicate entries if pdf.landing and pdf.tools resolve to same URL
                $alreadyAdded = array_filter($urls, fn($u) => $u['loc'] === $loc);
                if (empty($alreadyAdded)) {
                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $nowIso,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        }

        // 3. Dynamic Tool Registry Pages (from config/tools.php)
        $tools = config('tools', []);
        foreach ($tools as $tool) {
            $routeName = $tool['route_name'] ?? null;
            if ($routeName && Route::has($routeName)) {
                $loc = route($routeName);
                $alreadyAdded = array_filter($urls, fn($u) => $u['loc'] === $loc);
                if (empty($alreadyAdded)) {
                    $urls[] = [
                        'loc' => $loc,
                        'lastmod' => $nowIso,
                        'changefreq' => 'weekly',
                        'priority' => '0.9',
                    ];
                }
            }
        }

        // 4. Published Blog Posts (Dynamic from DB)
        try {
            $posts = BlogBuilderPost::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->get();

            foreach ($posts as $post) {
                if ($post->slug && Route::has('blog.show')) {
                    $urls[] = [
                        'loc' => route('blog.show', $post->slug),
                        'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Log fallback error if blog table is missing or DB issue occurs
            \Illuminate\Support\Facades\Log::error("Sitemap post fetch error: " . $e->getMessage());
        }

        // Build XML Output strictly adhering to sitemaps.org 0.9 schema
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . e($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . e($url['lastmod']) . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . e($url['changefreq']) . '</changefreq>' . "\n";
            $xml .= '    <priority>' . e($url['priority']) . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}

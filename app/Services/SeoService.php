<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Render standard meta tags, canonical link, Open Graph, and Twitter Cards dynamically.
     */
    public static function renderMeta(array $options = []): string
    {
        $siteName = config('site.name', 'CypherLab');
        $devName = config('site.developer.name', 'Brijesh Verma');
        $devAlias = config('site.developer.alias', 'Programmer Brijesh');
        $developer = "{$devName} ({$devAlias})";

        $defaultTitle = config('site.name') . ' - ' . config('site.tagline');
        $title = $options['title'] ?? $defaultTitle;
        if (!Str::contains($title, $siteName)) {
            $title .= ' | ' . $siteName;
        }

        $description = $options['description'] ?? config('site.description');
        $canonical = $options['canonical'] ?? url()->current();
        $robots = $options['robots'] ?? 'index, follow';
        $image = $options['image'] ?? asset(config('site.default_og_image', 'assets/images/og-default.png'));
        $type = $options['type'] ?? 'website';

        $html = [];
        $html[] = '<title>' . e($title) . '</title>';
        $html[] = '<meta name="description" content="' . e($description) . '">';
        $html[] = '<meta name="robots" content="' . e($robots) . '">';
        $html[] = '<link rel="canonical" href="' . e($canonical) . '">';
        $html[] = '<meta name="author" content="' . e($developer) . '">';

        // Open Graph
        $html[] = '<meta property="og:site_name" content="' . e($siteName) . '">';
        $html[] = '<meta property="og:title" content="' . e($title) . '">';
        $html[] = '<meta property="og:description" content="' . e($description) . '">';
        $html[] = '<meta property="og:url" content="' . e($canonical) . '">';
        $html[] = '<meta property="og:type" content="' . e($type) . '">';
        $html[] = '<meta property="og:image" content="' . e($image) . '">';

        // Twitter Card
        $html[] = '<meta name="twitter:card" content="summary_large_image">';
        $html[] = '<meta name="twitter:title" content="' . e($title) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($description) . '">';
        $html[] = '<meta name="twitter:image" content="' . e($image) . '">';

        return implode("\n    ", $html);
    }

    /**
     * Generate JSON-LD WebSite & Person Schema dynamically.
     */
    public static function generateWebSiteSchema(): string
    {
        $siteName = config('site.name', 'CypherLab');
        $devName = config('site.developer.name', 'Brijesh Verma');
        $devAlias = config('site.developer.alias', 'Programmer Brijesh');

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '/#website',
                    'url' => url('/'),
                    'name' => $siteName,
                    'description' => config('site.description'),
                    'publisher' => [
                        '@type' => 'Person',
                        '@id' => url('/') . '/#author',
                        'name' => $devName,
                        'alternateName' => $devAlias,
                        'jobTitle' => config('site.developer.title'),
                        'url' => route('about'),
                    ],
                ],
                [
                    '@type' => 'Person',
                    '@id' => url('/') . '/#author',
                    'name' => $devName,
                    'alternateName' => $devAlias,
                    'url' => route('about'),
                    'knowsAbout' => ['Software Engineering', 'Laravel', 'Web Development', 'Developer Tools', 'Cybersecurity'],
                ]
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generate JSON-LD SoftwareApplication Schema for Tool pages dynamically.
     */
    public static function generateSoftwareSchema(string $name, string $description, string $url, string $category = 'DeveloperApplication'): string
    {
        $devName = config('site.developer.name', 'Brijesh Verma');
        $devAlias = config('site.developer.alias', 'Programmer Brijesh');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'applicationCategory' => $category,
            'operatingSystem' => 'All (Browser-based)',
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'USD',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $devName,
                'alternateName' => $devAlias,
                'url' => route('about'),
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generate JSON-LD BreadcrumbList Schema dynamically.
     */
    public static function generateBreadcrumbSchema(array $items): string
    {
        $listList = [];
        $position = 1;

        foreach ($items as $name => $url) {
            $listList[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
                'item' => $url,
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listList,
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generate JSON-LD FAQPage Schema dynamically.
     */
    public static function generateFaqSchema(array $faqs): string
    {
        $elements = [];
        foreach ($faqs as $question => $answer) {
            $elements[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($answer),
                ],
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $elements,
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}

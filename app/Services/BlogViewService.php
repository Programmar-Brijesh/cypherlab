<?php

namespace App\Services;

use App\Models\BlogBuilderPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BlogViewService
{
    /**
     * Cache expiration window in hours.
     */
    public const WINDOW_HOURS = 24;

    /**
     * Record a unique article view if eligible within the 24-hour window.
     *
     * @param BlogBuilderPost $post
     * @param Request $request
     * @return bool True if a new view was counted and incremented; false otherwise.
     */
    public function recordView(BlogBuilderPost $post, Request $request): bool
    {
        // 1. Never count unpublished, draft, or unsaved articles
        if (!$post->exists || $post->status !== 'published') {
            return false;
        }

        // 2. Exclude bots, web crawlers, and scrapers
        if ($this->isBot($request)) {
            return false;
        }

        // 3. Obtain a privacy-conscious visitor hash
        $visitorHash = $this->getVisitorHash($request);

        // 4. Unique cache key scoped to the article and visitor
        $cacheKey = "blog_view:{$post->id}:{$visitorHash}";

        // 5. Atomic check-and-set via Cache::add (returns true only if key did not exist)
        $ttl = now()->addHours(self::WINDOW_HOURS);
        $isUniqueVisit = Cache::add($cacheKey, 1, $ttl);

        if (!$isUniqueVisit) {
            return false;
        }

        // 6. Atomically increment the views column in database
        BlogBuilderPost::where('id', $post->id)->increment('views');

        // 7. Update in-memory property so the current request reflects the increment
        $post->views = (int) $post->views + 1;

        return true;
    }

    /**
     * Determine if the incoming request is from a known crawler or bot.
     */
    public function isBot(Request $request): bool
    {
        $userAgent = $request->userAgent();

        if (empty($userAgent)) {
            return true;
        }

        $botPatterns = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'facebookexternalhit',
            'whatsapp', 'telegram', 'twitterbot', 'pinterest', 'linkedinbot',
            'embedly', 'quora', 'googlebot', 'bingbot', 'yandex', 'duckduckbot',
            'baiduspider', 'ahrefs', 'semrush', 'dotbot', 'petalbot', 'mj12bot',
            'screaming frog', 'uptimerobot', 'headlesschrome', 'lighthouse', 'bytespider'
        ];

        return Str::contains(strtolower($userAgent), $botPatterns);
    }

    /**
     * Generate a privacy-conscious, secure visitor hash.
     * Uses session / first-party cookie token without exposing raw IP addresses.
     */
    public function getVisitorHash(Request $request): string
    {
        // 1. Check for secure first-party visitor cookie
        $token = $request->cookie('cypher_vid');

        // 2. Fall back to session if available
        if (!$token && $request->hasSession()) {
            $token = $request->session()->get('visitor_token');
        }

        // 3. Generate a new token if none found
        if (!$token) {
            $token = Str::uuid()->toString();

            if ($request->hasSession()) {
                $request->session()->put('visitor_token', $token);
            }

            cookie()->queue(
                cookie('cypher_vid', $token, 60 * 24 * 365, null, null, false, true, false, 'Lax')
            );
        }

        // Return a secure HMAC SHA-256 hash using the app key
        $appKey = config('app.key') ?: 'cypherlabs-secret-key';
        return hash_hmac('sha256', $token, $appKey);
    }
}


<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\User;
use App\Services\BlogViewService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class BlogViewCountTest extends TestCase
{
    use RefreshDatabase;

    protected User $author;
    protected BlogCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->author = User::create([
            'name' => 'Brijesh Verma',
            'email' => 'author_' . Str::random(6) . '@cypherlabs.in',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->category = BlogCategory::create([
            'name' => 'Engineering',
            'slug' => 'engineering-' . Str::random(6),
        ]);
    }

    protected function createPost(array $attributes = []): BlogBuilderPost
    {
        return BlogBuilderPost::create(array_merge([
            'user_id' => $this->author->id,
            'title' => 'High-Performance Web Architecture Guide',
            'slug' => 'high-performance-web-architecture-' . Str::random(6),
            'category_id' => $this->category->id,
            'excerpt' => 'Detailed technical breakdown of web performance optimizations.',
            'html' => '<h2>Introduction</h2><p>Article body content.</p>',
            'status' => 'published',
            'published_at' => now(),
            'views' => 0,
        ], $attributes));
    }

    /**
     * 1. First visit increments views by 1.
     */
    public function test_first_visit_increments_views(): void
    {
        $post = $this->createPost();

        $response = $this->get(route('article.show', $post->slug));

        $response->assertStatus(200);
        $this->assertEquals(1, $post->fresh()->views);
        $response->assertSee('1 views');
    }

    /**
     * 2. Refresh same article does not increment again.
     */
    public function test_refresh_same_article_does_not_increment(): void
    {
        $post = $this->createPost();

        $this->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);

        // Immediate refresh within the same session/cookie
        $this->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);
    }

    /**
     * 3. Same visitor opening the same article within 24 hours does not increment.
     */
    public function test_same_visitor_within_24_hours_does_not_increment(): void
    {
        $post = $this->createPost();

        // Visit at T=0
        $this->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);

        // Travel 12 hours forward
        Carbon::setTestNow(now()->addHours(12));

        $this->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);

        Carbon::setTestNow(); // Reset time
    }

    /**
     * 4. Same visitor opening another article increments its count independently.
     */
    public function test_same_visitor_opening_another_article_increments_independently(): void
    {
        $postA = $this->createPost(['title' => 'Article Alpha']);
        $postB = $this->createPost(['title' => 'Article Beta']);

        // Same visitor visits Post A
        $this->get(route('article.show', $postA->slug));
        $this->assertEquals(1, $postA->fresh()->views);
        $this->assertEquals(0, $postB->fresh()->views);

        // Same visitor visits Post B
        $this->get(route('article.show', $postB->slug));
        $this->assertEquals(1, $postA->fresh()->views);
        $this->assertEquals(1, $postB->fresh()->views);
    }

    /**
     * 5. Different visitors increment article views independently.
     */
    public function test_different_visitors_increment_independently(): void
    {
        $post = $this->createPost();

        // Visitor 1
        $this->withCookie('cypher_vid', 'visitor-token-1')
            ->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);

        // Visitor 2
        $this->withCookie('cypher_vid', 'visitor-token-2')
            ->get(route('article.show', $post->slug));
        $this->assertEquals(2, $post->fresh()->views);
    }

    /**
     * 6. Expired 24-hour tracking allows another increment.
     */
    public function test_expired_24_hour_window_allows_another_increment(): void
    {
        $post = $this->createPost();

        $token = 'visitor-token-time-test';

        // Initial visit at T=0
        $this->withCookie('cypher_vid', $token)
            ->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);

        // Advance time by 25 hours (past the 24-hour window)
        Carbon::setTestNow(now()->addHours(25));

        $this->withCookie('cypher_vid', $token)
            ->get(route('article.show', $post->slug));
        $this->assertEquals(2, $post->fresh()->views);

        Carbon::setTestNow(); // Reset time
    }

    /**
     * 7. Unpublished or draft articles are not counted.
     */
    public function test_unpublished_articles_are_not_counted(): void
    {
        $draftPost = $this->createPost([
            'status' => 'draft',
            'published_at' => null,
            'views' => 0,
        ]);

        // Attempting to visit draft route returns 404
        $response = $this->get(route('article.show', $draftPost->slug));
        $response->assertStatus(404);
        $this->assertEquals(0, $draftPost->fresh()->views);

        // Directly invoking service with draft post also returns false
        $service = app(BlogViewService::class);
        $counted = $service->recordView($draftPost, Request::create('/'));
        $this->assertFalse($counted);
        $this->assertEquals(0, $draftPost->fresh()->views);
    }

    /**
     * 8. Bot and crawler requests are excluded.
     */
    public function test_bot_requests_are_excluded(): void
    {
        $post = $this->createPost();

        // Googlebot
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])
            ->get(route('article.show', $post->slug));
        $this->assertEquals(0, $post->fresh()->views);

        // AhrefsBot
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)'])
            ->get(route('article.show', $post->slug));
        $this->assertEquals(0, $post->fresh()->views);

        // Real browser
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'])
            ->get(route('article.show', $post->slug));
        $this->assertEquals(1, $post->fresh()->views);
    }

    /**
     * 9. Concurrent requests do not produce duplicate increments.
     */
    public function test_concurrent_requests_do_not_produce_duplicate_increments(): void
    {
        $post = $this->createPost();
        $service = app(BlogViewService::class);

        $request = Request::create(route('article.show', $post->slug));
        $request->cookies->set('cypher_vid', 'concurrent-tester-token');

        // Simulate two near-simultaneous calls
        $result1 = $service->recordView($post, $request);
        $result2 = $service->recordView($post, $request);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertEquals(1, $post->fresh()->views);
    }

    /**
     * 10. Existing article rendering and metadata remains intact.
     */
    public function test_article_rendering_remains_functional(): void
    {
        $post = $this->createPost([
            'html' => '<h2>Detailed Section</h2><p>Technical architecture explanation.</p>',
        ]);

        $response = $this->get(route('article.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('Detailed Section');
        $response->assertSee('1 views');
        $response->assertSee('"@context": "https://schema.org"', false);
    }
}


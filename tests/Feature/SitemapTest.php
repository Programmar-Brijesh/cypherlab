<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected BlogCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Tech Author',
            'email' => 'author_' . Str::random(5) . '@cypherlabs.in',
            'password' => bcrypt('password123'),
        ]);

        $this->category = BlogCategory::create([
            'name' => 'Architecture',
            'slug' => 'architecture',
        ]);
    }

    protected function createPost(array $attributes = []): BlogBuilderPost
    {
        return BlogBuilderPost::forceCreate(array_merge([
            'user_id' => $this->user->id,
            'title' => 'Sample Engineering Post',
            'slug' => 'sample-engineering-post-' . Str::random(6),
            'category_id' => $this->category->id,
            'excerpt' => 'Technical breakdown of microservices architecture.',
            'html' => '<p>Article content</p>',
            'status' => 'published',
            'published_at' => now(),
        ], $attributes));
    }

    /**
     * 1. Published blogs appear in sitemap.
     */
    public function test_published_blogs_appear_in_sitemap(): void
    {
        $post = $this->createPost([
            'title' => 'Scaling Kubernetes Pods',
            'slug' => 'scaling-kubernetes-pods',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(route('blog.show', 'scaling-kubernetes-pods'));
    }

    /**
     * 2. Draft blogs do not appear.
     */
    public function test_draft_blogs_do_not_appear(): void
    {
        $post = $this->createPost([
            'title' => 'Unfinished Draft Article',
            'slug' => 'unfinished-draft-article',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertDontSee('unfinished-draft-article');
    }

    /**
     * 3. Unpublished (scheduled in future) blogs do not appear.
     */
    public function test_unpublished_future_blogs_do_not_appear(): void
    {
        $post = $this->createPost([
            'title' => 'Future Scheduled Post',
            'slug' => 'future-scheduled-post',
            'status' => 'published',
            'published_at' => now()->addDays(5),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertDontSee('future-scheduled-post');
    }

    /**
     * 4. Soft-deleted blogs do not appear.
     */
    public function test_deleted_blogs_do_not_appear(): void
    {
        $post = $this->createPost([
            'title' => 'Deleted Post',
            'slug' => 'deleted-post-sample',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        // Soft delete the post
        $post->delete();

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertDontSee('deleted-post-sample');
    }

    /**
     * 5. Newly published blogs appear dynamically without manual editing.
     */
    public function test_newly_published_blogs_appear_immediately(): void
    {
        $responseInitial = $this->get('/sitemap.xml');
        $responseInitial->assertDontSee('instant-live-article');

        // Admin publishes new post
        $newPost = $this->createPost([
            'title' => 'Instant Live Article',
            'slug' => 'instant-live-article',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $responseAfter = $this->get('/sitemap.xml');
        $responseAfter->assertStatus(200);
        $responseAfter->assertSee(route('blog.show', 'instant-live-article'));
    }

    /**
     * 6. Canonical URLs are correct (standard blog.show route and custom canonical_url).
     */
    public function test_canonical_urls_are_accurate(): void
    {
        // Standard post without custom canonical URL: uses route('blog.show', $slug)
        $standardPost = $this->createPost([
            'slug' => 'standard-canonical-post',
            'canonical_url' => null,
        ]);

        // Post with custom canonical URL
        $customCanonicalPost = $this->createPost([
            'slug' => 'custom-canonical-post',
            'canonical_url' => 'https://cypherlab.in/blog/custom-canonical-post',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(route('blog.show', 'standard-canonical-post'));
        $response->assertSee('https://cypherlab.in/blog/custom-canonical-post');
    }

    /**
     * 7. XML response has the correct content type and valid XML structure.
     */
    public function test_xml_response_has_valid_content_type_and_schema(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', false);
        $response->assertSee('</urlset>', false);
        $response->assertSee('<loc>', false);
        $response->assertSee('</loc>', false);
    }

    /**
     * 8. Static core and tool URLs remain included.
     */
    public function test_static_and_tool_urls_remain_included(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(route('home'));
        $response->assertSee(route('about'));
        $response->assertSee(route('blog.index'));
        $response->assertSee(route('contact'));
        $response->assertSee(route('privacy'));
        $response->assertSee(route('terms'));

        // Core tools
        $response->assertSee(route('tools.qr-generator'));
        $response->assertSee(route('tools.image-compressor'));
        $response->assertSee(route('pdf.tools'));
    }

    /**
     * 9. Production canonical domain https://cypherlab.in is resolved when configured.
     */
    public function test_production_canonical_domain_resolution(): void
    {
        config(['app.url' => 'https://cypherlab.in']);

        $post = $this->createPost([
            'slug' => 'production-domain-test',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('https://cypherlab.in/blog/production-domain-test');
        $response->assertSee('https://cypherlab.in/about');
        $response->assertSee('https://cypherlab.in/tools/qr-generator');
    }
}

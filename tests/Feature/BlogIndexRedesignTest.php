<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BlogIndexRedesignTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected BlogCategory $catLaravel;
    protected BlogCategory $catDevops;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Alex Engineer',
            'email' => 'alex_' . Str::random(5) . '@cypherlabs.in',
            'password' => bcrypt('secret123'),
        ]);

        $this->catLaravel = BlogCategory::create([
            'name' => 'Laravel Framework',
            'slug' => 'laravel-framework',
        ]);

        $this->catDevops = BlogCategory::create([
            'name' => 'DevOps & SRE',
            'slug' => 'devops-sre',
        ]);
    }

    protected function createPost(array $attributes = []): BlogBuilderPost
    {
        return BlogBuilderPost::forceCreate(array_merge([
            'user_id' => $this->user->id,
            'title' => 'Building Scalable APIs with Laravel',
            'slug' => 'building-scalable-apis-' . Str::random(6),
            'category_id' => $this->catLaravel->id,
            'excerpt' => 'Architecting robust, low-latency microservices with Laravel 12.',
            'html' => '<p>Article technical walkthrough.</p>',
            'status' => 'published',
            'published_at' => now(),
            'reading_time' => 7,
            'views' => 1420,
            'is_featured' => false,
        ], $attributes));
    }

    /**
     * Test blog index renders successfully with required hero section.
     */
    public function test_blog_index_renders_hero_section(): void
    {
        $response = $this->get(route('blog.index'));

        $response->assertStatus(200);
        $response->assertSee('Engineering Insights & <span class="grad-text">Developer Guides</span>', false);
        $response->assertSee('CYPHERLABS KNOWLEDGE HUB');
        $response->assertSee('Search articles by title, topic, or keyword...');
    }

    /**
     * Test featured article appears prominently and is NOT duplicated in latest articles.
     */
    public function test_featured_article_is_displayed_and_not_duplicated(): void
    {
        $featured = $this->createPost([
            'title' => 'Mastering CQRS Pattern in PHP',
            'slug' => 'mastering-cqrs-pattern-in-php',
            'is_featured' => true,
            'views' => 4500,
        ]);

        $regular1 = $this->createPost([
            'title' => 'Kubernetes Pod Scheduling Deep Dive',
            'slug' => 'k8s-pod-scheduling-deep-dive',
            'category_id' => $this->catDevops->id,
        ]);

        $regular2 = $this->createPost([
            'title' => 'Event-Driven Systems with Redis Streams',
            'slug' => 'event-driven-redis-streams',
        ]);

        $response = $this->get(route('blog.index'));

        $response->assertStatus(200);

        // Featured article showcase section
        $response->assertSee('Featured Editorial');
        $response->assertSee('Mastering CQRS Pattern in PHP');
        $response->assertSee('4,500');

        // Check view data passed to Blade:
        // $response->viewData('featuredPost') is $featured
        $this->assertEquals($featured->id, $response->viewData('featuredPost')->id);

        // $posts paginator must NOT include the featured post ID
        $postIdsInGrid = $response->viewData('posts')->pluck('id')->toArray();
        $this->assertNotContains($featured->id, $postIdsInGrid);
        $this->assertContains($regular1->id, $postIdsInGrid);
        $this->assertContains($regular2->id, $postIdsInGrid);
    }

    /**
     * Test category filtering limits results to that category.
     */
    public function test_category_filtering(): void
    {
        $postLaravel = $this->createPost([
            'title' => 'Deep Dive into Laravel Octane',
            'slug' => 'deep-dive-laravel-octane',
            'category_id' => $this->catLaravel->id,
        ]);

        $postDevops = $this->createPost([
            'title' => 'Terraform AWS Infrastructure As Code',
            'slug' => 'terraform-aws-iac',
            'category_id' => $this->catDevops->id,
        ]);

        $response = $this->get(route('blog.index', ['category' => 'laravel-framework']));

        $response->assertStatus(200);
        $response->assertSee('Articles in Laravel Framework');
        $response->assertSee('Deep Dive into Laravel Octane');
        $response->assertDontSee('Terraform AWS Infrastructure As Code');

        $gridIds = $response->viewData('posts')->pluck('id')->toArray();
        $this->assertContains($postLaravel->id, $gridIds);
        $this->assertNotContains($postDevops->id, $gridIds);
    }

    /**
     * Test search filtering by keyword.
     */
    public function test_search_filtering(): void
    {
        $match = $this->createPost([
            'title' => 'Zero-Downtime Deployment Strategies',
            'slug' => 'zero-downtime-deployments',
            'excerpt' => 'Blue-green and Canary deployments with Envoy.',
        ]);

        $noMatch = $this->createPost([
            'title' => 'CSS Subgrid Complete Tutorial',
            'slug' => 'css-subgrid-complete-tutorial',
            'excerpt' => 'Modern CSS layout patterns.',
        ]);

        $response = $this->get(route('blog.index', ['search' => 'Deployment']));

        $response->assertStatus(200);
        $response->assertSee('Search Results for "Deployment"', false);
        $response->assertSee('Zero-Downtime Deployment Strategies');
        $response->assertDontSee('CSS Subgrid Complete Tutorial');
    }

    /**
     * Test empty state when no articles match search.
     */
    public function test_empty_state_on_no_match(): void
    {
        $this->createPost();

        $response = $this->get(route('blog.index', ['search' => 'NonExistentXYZWord123']));

        $response->assertStatus(200);
        $response->assertSee('No Articles Found');
        $response->assertSee('View All Articles');
    }

    /**
     * Test popular topics displays only database categories with correct counts.
     */
    public function test_popular_topics_displays_database_categories(): void
    {
        $this->createPost(['category_id' => $this->catLaravel->id]);
        $this->createPost(['category_id' => $this->catLaravel->id]);
        $this->createPost(['category_id' => $this->catDevops->id]);

        $response = $this->get(route('blog.index'));

        $response->assertStatus(200);
        $response->assertSee('Popular Topics');
        $response->assertSee('Laravel Framework');
        $response->assertSee('DevOps &amp; SRE', false);
    }

    /**
     * Test pagination links appear when posts exceed 9 items.
     */
    public function test_pagination_when_posts_exceed_page_limit(): void
    {
        // 1 featured + 10 regular = 11 published posts (paginate is 9 per page)
        for ($i = 1; $i <= 11; $i++) {
            $this->createPost([
                'title' => "Post Number {$i}",
                'slug' => "post-number-{$i}-" . Str::random(4),
            ]);
        }

        $response = $this->get(route('blog.index'));

        $response->assertStatus(200);
        $response->assertSee('blog-pagination-wrapper');
        $response->assertSee('page-item');
    }

    /**
     * Test featured article selection respects active category filter.
     */
    public function test_featured_article_respects_category_filter(): void
    {
        // Featured in DevOps
        $devopsFeatured = $this->createPost([
            'title' => 'DevOps Architecture Mastery',
            'slug' => 'devops-arch-mastery',
            'category_id' => $this->catDevops->id,
            'is_featured' => true,
        ]);

        // Regular post in Laravel (not featured)
        $laravelRegular = $this->createPost([
            'title' => 'Laravel Routing Secrets',
            'slug' => 'laravel-routing-secrets',
            'category_id' => $this->catLaravel->id,
            'is_featured' => false,
        ]);

        // Visit Laravel category page: DevOps featured post must NOT appear as featured
        $response = $this->get(route('blog.index', ['category' => 'laravel-framework']));

        $response->assertStatus(200);
        $this->assertNull($response->viewData('featuredPost'));
        $response->assertDontSee('DevOps Architecture Mastery');
        $response->assertSee('Laravel Routing Secrets');
    }

    /**
     * Test featured article selection respects active search query.
     */
    public function test_featured_article_respects_search_filter(): void
    {
        // Featured post about Docker
        $dockerFeatured = $this->createPost([
            'title' => 'Docker Multi-Stage Build Guide',
            'slug' => 'docker-multistage-builds',
            'is_featured' => true,
        ]);

        // Search for Postgres: Docker post must NOT appear as featured
        $response = $this->get(route('blog.index', ['search' => 'Postgres']));

        $response->assertStatus(200);
        $this->assertNull($response->viewData('featuredPost'));
        $response->assertDontSee('Docker Multi-Stage Build Guide');
    }

    /**
     * Test combined search and category filtering with pagination query preservation.
     */
    public function test_search_and_category_filters_work_together_and_pagination_preserves_both(): void
    {
        // Create 12 posts matching category Laravel and containing keyword 'Testing'
        for ($i = 1; $i <= 12; $i++) {
            $this->createPost([
                'title' => "Testing Laravel App Part {$i}",
                'slug' => "testing-laravel-app-part-{$i}-" . Str::random(4),
                'category_id' => $this->catLaravel->id,
            ]);
        }

        // Post in DevOps also matching keyword 'Testing'
        $devopsTesting = $this->createPost([
            'title' => 'Testing Kubernetes Deployments',
            'slug' => 'testing-k8s-deployments',
            'category_id' => $this->catDevops->id,
        ]);

        $response = $this->get(route('blog.index', [
            'category' => 'laravel-framework',
            'search' => 'Testing',
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('Testing Kubernetes Deployments');
        $response->assertSee('Testing Laravel App Part 1');

        // Check that pagination links preserve both 'category' and 'search'
        $response->assertSee('category=laravel-framework');
        $response->assertSee('search=Testing');
    }
}

<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_page_renders_successfully_with_schema(): void
    {
        $author = User::create([
            'name' => 'Brijesh Verma',
            'email' => 'brijesh@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $category = BlogCategory::create([
            'name' => 'Tools',
            'slug' => 'tools',
        ]);

        $post = BlogBuilderPost::create([
            'user_id' => $author->id,
            'title' => 'Free QR Code Generator Online - Create Custom QR Codes | CypherLabs',
            'slug' => 'free-qr-code-generator-online-create-custom-qr-codes-cypherlabs',
            'category_id' => $category->id,
            'excerpt' => 'Generate high quality QR codes for URLs, WiFi, contact info, and more.',
            'html' => '<h2>Why Use QR Codes?</h2><p>QR codes bridge offline and online worlds seamlessly.</p>',
            'css' => 'h2 { color: #00d4ff; }',
            'js' => 'console.log("QR Code article loaded");',
            'seo_title' => 'Free QR Code Generator Online - Create Custom QR Codes',
            'meta_description' => 'Generate high quality QR codes for URLs, WiFi, contact info, and more with CypherLabs.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('article.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('Free QR Code Generator Online');
        $response->assertSee('"@context": "https://schema.org"', false);
        $response->assertSee('"@type": "Article"', false);
        $response->assertSee('Why Use QR Codes?');
    }
}


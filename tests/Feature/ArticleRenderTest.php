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

    public function test_article_with_multiple_headings_renders_toc_elements(): void
    {
        $author = User::create([
            'name' => 'Brijesh Verma',
            'email' => 'brijesh2@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $post = BlogBuilderPost::create([
            'user_id' => $author->id,
            'title' => 'Long Multi-Heading Guide for Testing TOC',
            'slug' => 'long-multi-heading-guide-for-testing-toc',
            'html' => '<h2>What Is a QR Code Generator?</h2><p>Overview text.</p><h2>Key Features</h2><p>Feature details.</p><h2>How to Create a QR Code</h2><p>Step by step instructions.</p><h3>Custom Colors and Styling</h3><p>Styling details.</p><h2>Frequently Asked Questions</h2><p>FAQ answers.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('article.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('id="wx-toc-card"', false);
        $response->assertSee('id="wx-toc-list"', false);
        $response->assertSee('wx-share-card', false);
        $response->assertSee('wx-explore-card', false);
        $response->assertSee('wx-post-main', false);
        $response->assertSee('What Is a QR Code Generator?');
        $response->assertSee('Key Features');
        $response->assertSee('How to Create a QR Code');
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('scrollActiveTocItemIntoView', false);
        $response->assertSee('applyResponsiveLayout', false);
    }
}


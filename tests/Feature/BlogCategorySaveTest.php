<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCategorySaveTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private BlogCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->category = BlogCategory::create([
            'name' => 'Test Engineering',
            'slug' => 'test-engineering',
        ]);
    }

    public function test_saving_blog_with_valid_category_succeeds(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.blog.save'), [
            'title' => 'Test Blog with Valid Category',
            'status' => 'draft',
            'category_id' => $this->category->id,
            'html' => '<p>Valid category content</p>',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('blog_builder_posts', [
            'title' => 'Test Blog with Valid Category',
            'category_id' => $this->category->id,
        ]);
    }

    public function test_saving_blog_without_category_stores_null(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.blog.save'), [
            'title' => 'Test Blog without Category',
            'status' => 'draft',
            'category_id' => null,
            'html' => '<p>No category content</p>',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('blog_builder_posts', [
            'title' => 'Test Blog without Category',
            'category_id' => null,
        ]);
    }

    public function test_saving_blog_with_empty_string_category_stores_null(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.blog.save'), [
            'title' => 'Test Blog with Empty Category',
            'status' => 'draft',
            'category_id' => '',
            'html' => '<p>Empty category content</p>',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('blog_builder_posts', [
            'title' => 'Test Blog with Empty Category',
            'category_id' => null,
        ]);
    }

    public function test_saving_blog_with_nonexistent_category_returns_422_validation_error(): void
    {
        $nonExistentCategoryId = 999999;
        $this->assertNull(BlogCategory::find($nonExistentCategoryId));

        $response = $this->actingAs($this->admin)->postJson(route('admin.blog.save'), [
            'title' => 'Test Blog with Invalid Category',
            'status' => 'draft',
            'category_id' => $nonExistentCategoryId,
            'html' => '<p>Invalid category content</p>',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);
        $response->assertJsonFragment([
            'category_id' => ['The selected category is invalid or no longer available.'],
        ]);
    }

    public function test_autosave_with_nonexistent_category_returns_422_validation_error(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.blog.autosave'), [
            'title' => 'Test AutoSave Invalid Category',
            'category_id' => 999999,
            'html' => '<p>Autosave invalid</p>',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);
    }

    public function test_editor_view_renders_database_categories(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.blog.create'));

        $response->assertStatus(200);
        $response->assertSee($this->category->name);
        $response->assertSee('value="' . $this->category->id . '"', false);
    }
}

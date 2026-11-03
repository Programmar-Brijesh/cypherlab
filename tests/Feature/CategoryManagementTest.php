<?php

namespace Tests\Feature;

use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin_' . Str::random(5) . '@cypherlabs.in',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user_' . Str::random(5) . '@cypherlabs.in',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);
    }

    /**
     * 1. Unauthenticated users cannot access category management.
     */
    public function test_unauthenticated_and_non_admin_users_cannot_access_category_management(): void
    {
        // Guest user redirected to login
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login.page'));

        // Regular (non-admin) user redirected to blog index
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertRedirect(route('blog.index'));
    }

    /**
     * 2. Admin can view category management page.
     */
    public function test_admin_can_view_category_management_page(): void
    {
        $cat = BlogCategory::create([
            'name' => 'Cloud Computing',
            'slug' => 'cloud-computing',
            'description' => 'AWS, GCP and distributed systems architecture.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Blog Category Management');
        $response->assertSee('Cloud Computing');
        $response->assertSee('/cloud-computing');
    }

    /**
     * 3. Admin can create a category.
     */
    public function test_admin_can_create_a_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'description' => 'LLM, RAG and modern AI agent tutorials.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('blog_categories', [
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'description' => 'LLM, RAG and modern AI agent tutorials.',
        ]);
    }

    /**
     * 4. Duplicate category names and slugs are rejected appropriately.
     */
    public function test_duplicate_category_names_and_slugs_are_rejected(): void
    {
        BlogCategory::create([
            'name' => 'Cybersecurity',
            'slug' => 'cybersecurity',
        ]);

        // Attempt duplicate name
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Cybersecurity',
            'slug' => 'cybersecurity-unique',
        ]);
        $response->assertSessionHasErrors(['name']);

        // Attempt duplicate slug
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Network Security',
            'slug' => 'cybersecurity',
        ]);
        $response->assertSessionHasErrors(['slug']);
    }

    /**
     * 5. Admin can edit a category.
     */
    public function test_admin_can_edit_a_category(): void
    {
        $category = BlogCategory::create([
            'name' => 'Web Design',
            'slug' => 'web-design',
            'description' => 'Old description',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Frontend Engineering',
            'slug' => 'frontend-engineering',
            'description' => 'Modern CSS, UI/UX and frameworks.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $category->refresh();
        $this->assertEquals('Frontend Engineering', $category->name);
        $this->assertEquals('frontend-engineering', $category->slug);
        $this->assertEquals('Modern CSS, UI/UX and frameworks.', $category->description);
    }

    /**
     * 6. Admin can delete an empty category.
     */
    public function test_admin_can_delete_an_empty_category(): void
    {
        $category = BlogCategory::create([
            'name' => 'Temporary Category',
            'slug' => 'temporary-category',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * 7. Category deletion with assigned articles requires reassignment.
     */
    public function test_category_deletion_with_assigned_articles_requires_reassignment(): void
    {
        $category = BlogCategory::create([
            'name' => 'Backend Development',
            'slug' => 'backend-development',
        ]);

        // Create an article in this category
        BlogBuilderPost::forceCreate([
            'user_id' => $this->admin->id,
            'title' => 'Scaling MySQL at CypherLabs',
            'slug' => 'scaling-mysql-cypherlabs',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Attempt delete without reassignment: must fail with session error
        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category->id));

        $response->assertSessionHasErrors(['reassign_category_id']);
        $this->assertDatabaseHas('blog_categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * 8. Articles remain intact after reassignment.
     */
    public function test_articles_remain_intact_after_reassignment(): void
    {
        $catOld = BlogCategory::create([
            'name' => 'Legacy Category',
            'slug' => 'legacy-category',
        ]);

        $catNew = BlogCategory::create([
            'name' => 'Modern Architecture',
            'slug' => 'modern-architecture',
        ]);

        $post1 = BlogBuilderPost::forceCreate([
            'user_id' => $this->admin->id,
            'title' => 'Monolith to Microservices',
            'slug' => 'monolith-to-microservices',
            'category_id' => $catOld->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $post2 = BlogBuilderPost::forceCreate([
            'user_id' => $this->admin->id,
            'title' => 'Event Driven Systems',
            'slug' => 'event-driven-systems',
            'category_id' => $catOld->id,
            'status' => 'draft',
        ]);

        // Delete with reassignment
        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $catOld->id), [
            'reassign_category_id' => $catNew->id,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        // catOld deleted
        $this->assertDatabaseMissing('blog_categories', [
            'id' => $catOld->id,
        ]);

        // Articles reassigned to catNew and completely intact
        $this->assertEquals($catNew->id, $post1->fresh()->category_id);
        $this->assertEquals($catNew->id, $post2->fresh()->category_id);
    }

    /**
     * 9. Newly created categories appear in the post editor.
     */
    public function test_newly_created_categories_appear_in_the_post_editor(): void
    {
        // Create category
        $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Rust & WebAssembly',
            'slug' => 'rust-webassembly',
        ]);

        // Visit post editor
        $response = $this->actingAs($this->admin)->get(route('admin.blog.create'));

        $response->assertStatus(200);
        $response->assertSee('Rust &amp; WebAssembly', false);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display category management listing with search and article counts.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = BlogCategory::query()
            ->withCount([
                'builderPosts as published_articles_count' => function ($q) {
                    $q->where('status', 'published');
                },
                'builderPosts as total_builder_count',
                'posts as legacy_posts_count',
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        // Calculate combined total articles (builder + legacy) for display
        $categories->getCollection()->transform(function ($category) {
            $category->total_articles_count = (int) $category->total_builder_count + (int) $category->legacy_posts_count;
            return $category;
        });

        // List of all existing categories for safe article reassignment dropdown
        $allCategories = BlogCategory::orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'search', 'allCategories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'A category with this name already exists.',
            'slug.unique' => 'A category with this URL slug already exists.',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        if (empty($slug)) {
            $slug = 'category-' . time();
        }

        // Guarantee unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $category = BlogCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' created successfully.");
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'name')->ignore($category->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'slug')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'The category name is required.',
            'name.unique' => 'Another category is already using this name.',
            'slug.unique' => 'Another category is already using this slug.',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        if (empty($slug)) {
            $slug = 'category-' . $category->id;
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category '{$category->name}' updated successfully.");
    }

    /**
     * Safely delete a category with article check and reassignment.
     */
    public function destroy(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $builderCount = $category->builderPosts()->count();
        $legacyCount = $category->posts()->count();
        $totalArticles = $builderCount + $legacyCount;

        // If category has assigned articles, require reassignment
        if ($totalArticles > 0) {
            $request->validate([
                'reassign_category_id' => [
                    'required',
                    'integer',
                    Rule::exists('blog_categories', 'id'),
                    Rule::notIn([$category->id]),
                ],
            ], [
                'reassign_category_id.required' => "This category contains {$totalArticles} articles. You must select another category to reassign them to before deletion.",
                'reassign_category_id.exists' => 'The selected target category does not exist.',
                'reassign_category_id.not_in' => 'Cannot reassign articles to the same category being deleted.',
            ]);

            $targetCategory = BlogCategory::findOrFail($request->reassign_category_id);

            DB::transaction(function () use ($category, $targetCategory) {
                // Reassign all builder posts
                BlogBuilderPost::where('category_id', $category->id)
                    ->update(['category_id' => $targetCategory->id]);

                // Reassign all legacy posts
                BlogPost::where('category_id', $category->id)
                    ->update(['category_id' => $targetCategory->id]);

                // Delete the category
                $category->delete();
            });

            return redirect()
                ->route('admin.categories.index')
                ->with('success', "Category '{$category->name}' deleted successfully. {$totalArticles} articles were safely reassigned to '{$targetCategory->name}'.");
        }

        // Safe direct delete when empty
        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category '{$name}' deleted successfully.");
    }
}

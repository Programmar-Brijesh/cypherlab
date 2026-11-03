<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Services\BlogViewService;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogpostController extends Controller
{
    public function __construct(
        protected BlogViewService $viewService
    ) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $categorySlug = trim((string) $request->query('category', ''));

        // Load categories that exist in the database with published post count
        $categories = BlogCategory::withCount(['builderPosts' => function ($q) {
            $q->where('status', 'published');
        }])->orderBy('name')->get();

        $selectedCategory = null;
        if (!empty($categorySlug)) {
            $selectedCategory = $categories->firstWhere('slug', $categorySlug)
                ?? BlogCategory::where('slug', $categorySlug)->first();
        }

        // Total published posts count (including uncategorized)
        $totalPublishedPosts = BlogBuilderPost::where('status', 'published')->count();

        // Dedicated Featured Article showcase:
        // Shown on initial page 1 view, strictly respecting active search and category filters
        $currentPage = (int) $request->query('page', 1);
        $featuredPost = null;

        if ($currentPage <= 1) {
            $featuredQuery = BlogBuilderPost::where('status', 'published')
                ->with(['category', 'author']);

            // Respect category filter for featured article
            if ($selectedCategory) {
                $featuredQuery->where('category_id', $selectedCategory->id);
            }

            // Respect search filter for featured article
            if (!empty($search)) {
                $featuredQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('meta_description', 'like', "%{$search}%");
                });
            }

            // Prefer explicitly featured post matching current filters
            $featuredPost = (clone $featuredQuery)
                ->where('is_featured', true)
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            // On default main index (no search, no category filter), fallback to latest post if none marked featured
            if (!$featuredPost && empty($search) && empty($categorySlug)) {
                $featuredPost = (clone $featuredQuery)
                    ->orderByDesc('published_at')
                    ->orderByDesc('created_at')
                    ->first();
            }
        }

        // Build latest articles query
        $query = BlogBuilderPost::where('status', 'published')
            ->with(['category', 'author']);

        // Prevent duplicating the featured article in the grid
        if ($featuredPost) {
            $query->where('id', '!=', $featuredPost->id);
        }

        // Filter by category if selected
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }

        // Filter by search keyword
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.blog.index', compact(
            'posts',
            'categories',
            'featuredPost',
            'selectedCategory',
            'search',
            'categorySlug',
            'totalPublishedPosts'
        ));
    }



    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
            'category_id' => [
                'nullable',
                Rule::exists('blog_categories', 'id'),
            ],
        ], [
            'category_id.exists' => 'The selected category is invalid or no longer available.',
        ]);

        if ($request->filled('post_id')) {
            $post = BlogBuilderPost::find($request->post_id) ?? new BlogBuilderPost();
        } else {
            $post = new BlogBuilderPost();
        }

        /*
    |--------------------------------------------------------------------------
    | Basic
    |--------------------------------------------------------------------------
    */

        $post->user_id = \Illuminate\Support\Facades\Auth::id() ?? (\Illuminate\Support\Facades\Session::get('user')->id ?? null);
        $post->title = $request->title;

        $baseSlug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        if (empty($baseSlug)) {
            $baseSlug = 'post-' . time();
        }

        if (!$post->exists || ($request->filled('slug') && $request->slug !== $post->slug)) {
            $slug = $baseSlug;
            $count = 1;
            while (BlogBuilderPost::where('slug', $slug)->where('id', '!=', $post->id ?? 0)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            $post->slug = $slug;
        }

        $post->excerpt = $request->excerpt;

        $post->category_id = $request->filled('category_id') ? (int) $request->category_id : null;

        /*
    |--------------------------------------------------------------------------
    | Editor Content
    |--------------------------------------------------------------------------
    */

        $post->html = $request->html;
        $post->css = $request->css;
        $post->js = $request->js;

        /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */

        $post->featured_image = $request->featured_image;

        /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */

        $post->seo_title = $request->seo_title;
        $post->meta_description = $request->meta_description;
        $post->canonical_url = $request->canonical_url;
        $post->og_image = $request->og_image;
        $post->keywords = $request->keywords;
        $post->schema_type = $request->schema_type ?? 'Article';

        /*
    |--------------------------------------------------------------------------
    | Reading Time
    |--------------------------------------------------------------------------
    */

        $plainText = strip_tags($request->html);

        $words = str_word_count($plainText);

        $post->reading_time = max(1, ceil($words / 200));

        /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

        $post->settings = json_encode([
            'preview' => 'desktop'
        ]);

        /*
    |--------------------------------------------------------------------------
    | Publish
    |--------------------------------------------------------------------------
    */

        $post->status = $request->status;

        if (
            $request->status === 'published' &&
            empty($post->published_at)
        ) {
            $post->published_at = now();
        }

        $post->save();

        return response()->json([
            'success' => true,
            'message' => $request->status === 'published'
                ? 'Blog published successfully.'
                : 'Draft saved successfully.',
            'post_id' => $post->id,
            'slug' => $post->slug,
            'url' => route('article.show', $post->slug),
            'redirect' => route('dashboard'),
        ]);
    }

    public function show(Request $request, $slug)
    {
        $post = BlogBuilderPost::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $this->viewService->recordView($post, $request);

        return view('frontend.blog.show', compact('post'));
    }
}

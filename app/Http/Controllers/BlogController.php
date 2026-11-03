<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller
{
    public function index()
    {
        $featuredPosts = BlogPost::where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        $recentPosts = BlogPost::where('is_featured', false)
            ->latest()
            ->take(6)
            ->get();

        $popularPosts = BlogPost::orderBy('views', 'desc')
            ->take(4)
            ->get();

        $categories = BlogCategory::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(8)
            ->get();

        $tags = BlogTag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(12)
            ->get();

        $totalPosts = BlogPost::count();
        $totalCategories = BlogCategory::count();
        $totalViews = BlogPost::sum('views');

        return view('blog.index', compact(
            'featuredPosts',
            'recentPosts',
            'popularPosts',
            'categories',
            'tags',
            'totalPosts',
            'totalCategories',
            'totalViews'
        ));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        // Category
        $category = BlogCategory::firstOrCreate(
            ['slug' => Str::slug($request->category ?? 'other')],
            ['name' => ucfirst($request->category ?? 'Other')]
        );

        // Featured Image
        $featuredImage = null;

        if ($request->hasFile('featured_image')) {

            $file = $request->file('featured_image');

            $featuredImage = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/blog'), $featuredImage);
        }

        // Create Post
        $post = BlogPost::create([
            'title'          => $request->title,
            'slug'           => Str::slug($request->title) . '-' . time(),
            'excerpt'        => $request->excerpt,
            'content'        => $request->content,
            'featured_image' => $featuredImage,

            'user_id'        => Session::get('user')->id,

            'category_id'    => $category->id,
            'published_at'   => $request->status === 'published'
                ? Carbon::now()
                : null,
        ]);

        // Tags
        if ($request->filled('tags')) {

            $tagIds = [];

            foreach (explode(',', $request->tags) as $tagName) {

                $tagName = trim($tagName);

                if (!$tagName) {
                    continue;
                }

                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );

                $tagIds[] = $tag->id;
            }

            $post->tags()->sync($tagIds);
        }

        return redirect()
            ->route('blog.show', $post->slug)
            ->with('success', 'Blog created successfully.');
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->with(['category', 'tags', 'author'])
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = BlogPost::where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest()
            ->take(3)
            ->get();

        $previousPost = BlogPost::where('id', '<', $post->id)->latest()->first();
        $nextPost = BlogPost::where('id', '>', $post->id)->oldest()->first();

        return view('blog.show', compact('post', 'relatedPosts', 'previousPost', 'nextPost'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = BlogPost::where('category_id', $category->id)
            ->latest()
            ->paginate(9);

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()->latest()->paginate(9);

        return view('blog.tag', compact('tag', 'posts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $posts = BlogPost::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->orWhere('excerpt', 'like', "%{$query}%")
            ->latest()
            ->paginate(9);

        return view('blog.search', compact('posts', 'query'));
    }

    public function archive($year, $month = null)
    {
        $query = BlogPost::whereYear('created_at', $year);

        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $posts = $query->latest()->paginate(9);

        return view('blog.archive', compact('posts', 'year', 'month'));
    }
}

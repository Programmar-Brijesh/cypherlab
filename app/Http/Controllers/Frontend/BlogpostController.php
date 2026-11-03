<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogpostController extends Controller
{
    public function index()
    {
        // Sirf published posts nikalenge, latest pehle dikhayenge, aur pagination lagayenge
        $posts = BlogBuilderPost::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(9); // Ek page par 9 posts



        return view('frontend.blog.index', compact('posts'));
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

    public function show($slug)
    {
        $post = BlogBuilderPost::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('frontend.blog.show', compact('post'));
    }
}

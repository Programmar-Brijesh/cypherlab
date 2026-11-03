<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogBuilderPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeveloperBlogController extends Controller
{
    public function create(Request $request)
    {
        $post = null;
        if ($request->has('post_id')) {
            $post = BlogBuilderPost::find($request->post_id);
        }
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blog.editor', compact('post', 'categories'));
    }

    public function edit($id)
    {
        $post = BlogBuilderPost::findOrFail($id);
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blog.editor', compact('post', 'categories'));
    }

    public function destroy($id)
    {
        $post = BlogBuilderPost::findOrFail($id);
        $post->delete();

        return redirect()->route('dashboard')->with('message', 'Blog post deleted successfully.');
    }

    public function autoSave(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'js' => 'nullable|string',
            'category_id' => [
                'nullable',
                Rule::exists('blog_categories', 'id'),
            ],
        ], [
            'category_id.exists' => 'The selected category is invalid or no longer available.',
        ]);

        $postId = $request->filled('post_id') ? $request->post_id : null;
        $post = $postId ? BlogBuilderPost::find($postId) : new BlogBuilderPost();
        if (!$post) {
            $post = new BlogBuilderPost();
        }

        $baseSlug = Str::slug($request->title) ?: 'post-' . time();
        if (!$post->exists) {
            $slug = $baseSlug;
            $count = 1;
            while (BlogBuilderPost::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            $post->slug = $slug;
        }

        $status = $request->status ?? 'draft';
        $publishedAt = null;
        if ($status === 'published') {
            $publishedAt = ($post->exists && $post->published_at) ? $post->published_at : now();
        }

        $post->user_id = \Illuminate\Support\Facades\Auth::id() ?? (\Illuminate\Support\Facades\Session::get('user')->id ?? null);
        $post->title = $request->title;
        $post->html = $request->html;
        $post->css = $request->css;
        $post->js = $request->js;
        $post->featured_image = $request->featured_image;
        $post->category_id = $request->filled('category_id') ? (int) $request->category_id : null;
        $post->seo_title = $request->seo_title;
        $post->meta_description = $request->meta_description;
        $post->keywords = $request->keywords;
        $post->canonical_url = $request->canonical_url;
        $post->schema_type = $request->schema_type ?? 'Article';
        $post->status = $status;
        if ($publishedAt) {
            $post->published_at = $publishedAt;
        }
        $post->save();

        return response()->json([
            'status' => 'success',
            'post_id' => $post->id,
            'message' => $status === 'published' ? 'Post Published Successfully!' : 'Draft Saved!',
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blog'), $filename);
            $url = asset('uploads/blog/' . $filename);

            return response()->json([
                'status' => 'success',
                'url' => $url,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'No image provided.'], 400);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogPostController extends Controller
{
    public function index()
    {
        return view('admin.blog.index', ['posts' => BlogPost::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.blog.form', ['post' => new BlogPost(), 'pageTitle' => 'Create Blog Post']);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['featured_image'] = $this->imageValue($request);
        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post created successfully.');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.blog.form', ['post' => $post, 'pageTitle' => 'Edit Blog Post']);
    }

    public function update(Request $request, BlogPost $post)
    {
        $data = $this->validated($request, $post);
        $data['featured_image'] = $this->imageValue($request, $post);
        $post->update($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $post)
    {
        $this->deleteLocalImage($post->featured_image);
        $post->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post deleted.');
    }

    private function validated(Request $request, BlogPost $post = null)
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('title'));
        $request->merge(['slug' => $slug]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['required', 'string', 'max:190', Rule::unique('blog_posts', 'slug')->ignore($post ? $post->id : null)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'url', 'max:2048'],
            'featured_image_file' => ['nullable', 'image', 'max:3072'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:180'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ]);

        unset($data['featured_image_file']);
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    private function imageValue(Request $request, BlogPost $post = null)
    {
        if ($request->hasFile('featured_image_file')) {
            if ($post) $this->deleteLocalImage($post->featured_image);
            return 'storage/'.$request->file('featured_image_file')->store('blog', 'public');
        }

        $url = $request->input('featured_image');
        if ($url) {
            if ($post && $url !== $post->featured_image) $this->deleteLocalImage($post->featured_image);
            return $url;
        }

        return $post ? $post->featured_image : null;
    }

    private function deleteLocalImage($path)
    {
        if ($path && Str::startsWith($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        }
    }
}

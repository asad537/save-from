<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog', [
            'posts' => BlogPost::published()->latest('published_at')->paginate(12),
            'title' => 'Media Download Guides & Tips — Save-Froms Blog',
            'description' => 'Explore practical public-media download guides for YouTube, Instagram, TikTok, Facebook, X, Vimeo, Dailymotion and Twitch links.',
        ]);
    }

    public function show($slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('blog-show', [
            'post' => $post,
            'relatedPosts' => BlogPost::published()
                ->where('id', '<>', $post->id)
                ->latest('published_at')
                ->latest('id')
                ->limit(3)
                ->get(),
            'title' => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->content), 160)),
            'ogImage' => $post->featured_image,
        ]);
    }
}

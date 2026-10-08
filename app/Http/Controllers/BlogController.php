<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\SupportedSite;
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
            'primaryPage' => $this->primaryPageFor($post),
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

    /**
     * Every article links to exactly one action page: the most specific tool
     * that matches the article's topic, falling back to the homepage downloader.
     */
    private function primaryPageFor(BlogPost $post)
    {
        $tokens = explode('-', $post->slug);
        $has = function (array $words) use ($tokens) {
            foreach ($words as $word) {
                if (!in_array($word, $tokens, true)) {
                    return false;
                }
            }
            return true;
        };

        $landingRules = [
            'youtube-shorts-downloader' => [['youtube', 'shorts']],
            'youtube-mp3-downloader' => [['youtube', 'mp3']],
            'youtube-to-mp4' => [['youtube', 'mp4']],
            'instagram-reels-downloader' => [['instagram', 'reels']],
            'instagram-stories-downloader' => [['instagram', 'stories']],
            'tiktok-mp3-downloader' => [['tiktok', 'mp3']],
            'tiktok-mp4-downloader' => [['tiktok', 'mp4']],
            'download-troubleshooting' => [['working'], ['403'], ['fixes']],
        ];
        foreach ($landingRules as $slug => $patterns) {
            foreach ($patterns as $pattern) {
                if ($has($pattern)) {
                    $page = LandingPage::active()->where('slug', $slug)->first();
                    if ($page) {
                        return ['url' => url('/'.$page->slug), 'label' => 'Open the '.$page->title, 'text' => 'This guide pairs with the '.$page->title.' page, where you can paste a link and compare the formats available for your source.'];
                    }
                }
            }
        }

        foreach (SupportedSite::POST_KEYWORDS as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (in_array($keyword, $tokens, true)) {
                    $site = SupportedSite::active()->where('slug', $slug)->first();
                    if ($site) {
                        return ['url' => url('/'.$site->slug), 'label' => 'Open the '.$site->headlineText(), 'text' => 'Paste a public '.$site->name.' link on the '.$site->headlineText().' page and compare the formats returned for your source.'];
                    }
                }
            }
        }

        return ['url' => route('home').'#downloader', 'label' => 'Open the downloader', 'text' => 'Return to the downloader and compare the formats available for your source.'];
    }
}

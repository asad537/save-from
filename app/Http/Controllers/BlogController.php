<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\SupportedSite;
use App\Support\GuideTopics;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $posts = BlogPost::published()->latest('published_at')->latest('id')->paginate(12);
        if ($page > 1 && $page > $posts->lastPage()) {
            abort(404);
        }
        $suffix = $page > 1 ? ' – Page '.$page : '';

        return view('blog', [
            'posts' => $posts,
            'title' => 'Video Download Guides'.$suffix.' | Save-Froms',
            'description' => $page > 1
                ? 'Page '.$page.' of the Save-Froms video download guides: links, formats, quality and fixes for YouTube, Instagram, TikTok, Facebook and more.'
                : 'Step-by-step guides for downloading public videos from YouTube, Instagram, TikTok, Facebook, X, Vimeo, Dailymotion and Twitch: links, formats, quality and fixes.',
            'canonical' => $page > 1 ? route('blog').'?page='.$page : route('blog'),
        ]);
    }

    public function topic(string $slug)
    {
        $topic = GuideTopics::find($slug);
        abort_unless($topic, 404);

        return view('guides-topic', [
            'topic' => $topic,
            'topics' => GuideTopics::all(),
            'posts' => GuideTopics::posts($slug),
            'title' => $topic['title'],
            'description' => $topic['description'],
        ]);
    }

    public function author()
    {
        return view('author', [
            'posts' => BlogPost::published()->latest('published_at')->latest('id')->get(),
            'platforms' => SupportedSite::active()->orderBy('sort_order')->get(),
            'title' => 'Alex Roy – Editor of the Save-Froms Download Guides',
            'description' => 'Alex Roy writes and maintains every Save-Froms download guide: who Alex is, what the guides cover, how each claim is tested, and the full list of articles.',
        ]);
    }

    public function show($slug)
    {
        $legacySlugs = [
            'download-video-yt-mobile-india-guide' => 'download-video-yt-mobile-guide',
            // Merged into the Facebook Reel downloader guide on 2026-10-27.
            'download-facebook-reels-android-iphone-files-guide' => 'facebook-reel-downloader-public-reels-mobile-guide',
            // 6 October short posts merged into their longer siblings on 2026-10-27.
            'dailymotion-video-url-guide-short-links-quality' => 'dailymotion-video-downloader-public-links-quality-guide',
            'vimeo-download-guide-public-links-privacy-quality' => 'download-vimeo-videos-public-links-passwords-privacy-guide',
            'tiktok-link-guide-mp4-mp3-short-urls' => 'tiktok-mp4-vs-mp3-download-format-guide',
            'instagram-reels-posts-stories-public-link-guide' => 'instagram-reels-download-quality-links-mobile-tips',
        ];
        if (isset($legacySlugs[$slug])) {
            return redirect()->route('blog.show', $legacySlugs[$slug], 301);
        }

        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view($post->language === 'ar' ? 'blog-show-ar' : 'blog-show', [
            'post' => $post,
            'primaryPage' => $this->primaryPageFor($post),
            'relatedPosts' => $this->relatedPostsFor($post),
            'title' => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: ($post->excerpt ?: Str::limit(strip_tags($post->content), 160)),
            'ogImage' => $post->featured_image,
            'language' => $post->language ?: 'en',
        ]);
    }

    /**
     * Articles about the same platform or topic first, then the newest guides,
     * so the related block differs from page to page.
     */
    private function relatedPostsFor(BlogPost $post)
    {
        $tokens = explode('-', $post->slug);
        $topical = ['youtube', 'shorts', 'instagram', 'reels', 'stories', 'tiktok', 'facebook', 'twitter', 'x', 'vimeo', 'dailymotion', 'twitch', 'mp3', 'mp4', 'resolution', 'savefrom', 'mobile', 'android', 'iphone', 'privacy', 'links', 'link', 'url'];
        $mine = array_values(array_intersect($tokens, $topical));

        $candidates = BlogPost::published()->where('id', '<>', $post->id)->where('language', $post->language ?: 'en')->latest('published_at')->latest('id')->get();

        return $candidates->sortByDesc(function ($candidate) use ($mine) {
            return count(array_intersect(explode('-', $candidate->slug), $mine));
        })->take(3)->values();
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
                        return ['url' => url('/'.$page->slug), 'label' => 'Open the '.$page->title, 'heading' => 'Put this guide to work on the '.$page->title, 'topic' => $page->title, 'text' => 'This guide pairs with the '.$page->title.' page, where you can paste a link and compare the formats available for your source.'];
                    }
                }
            }
        }

        foreach (SupportedSite::POST_KEYWORDS as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (in_array($keyword, $tokens, true)) {
                    $site = SupportedSite::active()->where('slug', $slug)->first();
                    if ($site) {
                        return ['url' => url('/'.$site->slug), 'label' => 'Open the '.$site->headlineText(), 'heading' => 'Have a '.$site->name.' link ready?', 'topic' => $site->name, 'text' => 'Paste a public '.$site->name.' link on the '.$site->headlineText().' page and compare the formats returned for your source.'];
                    }
                }
            }
        }

        return ['url' => route('home').'#downloader', 'label' => 'Open the downloader', 'heading' => 'Ready to check a public media link?', 'topic' => 'Guides', 'text' => 'Return to the downloader and compare the formats available for your source.'];
    }
}

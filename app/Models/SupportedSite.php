<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SupportedSite extends Model
{
    protected $fillable = [
        'name', 'slug', 'domains', 'logo_url', 'description', 'heading',
        'meta_title', 'meta_description', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    /**
     * Default H1 per platform. Square brackets mark the highlighted part.
     */
    const DEFAULT_HEADINGS = [
        'youtube-downloader' => '[YouTube] Video Downloader',
        'instagram-downloader' => '[Instagram] Video Downloader',
        'tiktok-downloader' => '[TikTok] Video Downloader',
        'facebook-downloader' => '[Facebook] Video Downloader',
        'twitter-downloader' => '[Twitter / X] Video Downloader',
        'vimeo-downloader' => '[Vimeo] Video Downloader',
        'dailymotion-downloader' => '[Dailymotion] Video Downloader',
        'twitch-downloader' => '[Twitch] Clip Downloader',
        'pinterest-downloader' => '[Pinterest] Video Downloader',
    ];

    /**
     * Specialist landing pages that belong under each platform hub.
     */
    const CHILD_PAGES = [
        'youtube-downloader' => ['youtube-shorts-downloader', 'youtube-to-mp4', 'youtube-mp3-downloader'],
        'instagram-downloader' => ['instagram-reels-downloader', 'instagram-stories-downloader'],
        'tiktok-downloader' => ['tiktok-mp4-downloader', 'tiktok-mp3-downloader'],
    ];

    /**
     * Slug tokens that identify blog posts about each platform.
     */
    const POST_KEYWORDS = [
        'youtube-downloader' => ['youtube'],
        'instagram-downloader' => ['instagram'],
        'tiktok-downloader' => ['tiktok'],
        'facebook-downloader' => ['facebook'],
        'twitter-downloader' => ['x', 'twitter'],
        'vimeo-downloader' => ['vimeo'],
        'dailymotion-downloader' => ['dailymotion'],
        'twitch-downloader' => ['twitch'],
        'pinterest-downloader' => ['pinterest'],
    ];

    /**
     * What each platform integration can usually return. Used by the supported-sites hub.
     */
    const CAPABILITIES = [
        'youtube-downloader' => ['video' => 'MP4 / WEBM', 'audio' => 'MP3', 'short' => 'Shorts', 'links' => 'watch, youtu.be, shorts'],
        'instagram-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => 'Reels', 'links' => '/reel/ and /p/ post links'],
        'tiktok-downloader' => ['video' => 'MP4', 'audio' => 'MP3', 'short' => 'All clips', 'links' => '/video/ and vm/vt short links'],
        'facebook-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => 'Reels', 'links' => 'videos, watch, reel, share'],
        'twitter-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => '—', 'links' => 'x.com and twitter.com status'],
        'vimeo-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => '—', 'links' => 'vimeo.com/ID'],
        'dailymotion-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => '—', 'links' => 'dailymotion.com/video, dai.ly'],
        'twitch-downloader' => ['video' => 'MP4 clips / VODs', 'audio' => '—', 'short' => 'Clips', 'links' => 'clips.twitch.tv, twitch.tv/videos'],
        'pinterest-downloader' => ['video' => 'MP4', 'audio' => '—', 'short' => '—', 'links' => 'pinterest.com/pin, pin.it'],
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function domainList()
    {
        return collect(explode(',', (string) $this->domains))
            ->map(function ($domain) { return strtolower(trim($domain)); })
            ->filter()
            ->values();
    }

    /**
     * H1 text with [brackets] marking the highlighted phrase.
     */
    public function headline()
    {
        if (!empty($this->heading)) {
            return $this->heading;
        }

        return self::DEFAULT_HEADINGS[$this->slug] ?? '['.$this->name.'] Video Downloader';
    }

    /**
     * Headline rendered as safe HTML with <em> around the bracketed phrase.
     */
    public function headlineHtml()
    {
        return preg_replace('/\[(.*?)\]/', '<em>$1</em>', e($this->headline()));
    }

    /**
     * Plain headline without bracket markers, for schema and link text.
     */
    public function headlineText()
    {
        return str_replace(['[', ']'], '', $this->headline());
    }

    public function capabilities()
    {
        return self::CAPABILITIES[$this->slug] ?? ['video' => 'Source-dependent', 'audio' => '—', 'short' => '—', 'links' => 'Public media links'];
    }

    public function childPages()
    {
        $slugs = self::CHILD_PAGES[$this->slug] ?? [];
        if (!$slugs) {
            return collect();
        }

        return LandingPage::active()->whereIn('slug', $slugs)->orderBy('sort_order')->get();
    }

    public function relatedPosts($limit = 4)
    {
        $keywords = self::POST_KEYWORDS[$this->slug] ?? [Str::slug($this->name)];

        return BlogPost::published()->latest('published_at')->latest('id')->get(['id', 'title', 'slug', 'excerpt', 'published_at'])
            ->filter(function ($post) use ($keywords) {
                $tokens = explode('-', $post->slug);
                foreach ($keywords as $keyword) {
                    if (in_array($keyword, $tokens, true)) {
                        return true;
                    }
                }
                return false;
            })->take($limit)->values();
    }

    public function brandIcon()
    {
        $icons = [
            'youtube-downloader' => 'bi-youtube',
            'instagram-downloader' => 'bi-instagram',
            'tiktok-downloader' => 'bi-tiktok',
            'facebook-downloader' => 'bi-facebook',
            'twitter-downloader' => 'bi-twitter-x',
            'vimeo-downloader' => 'bi-vimeo',
            'dailymotion-downloader' => 'bi-play-circle-fill',
            'twitch-downloader' => 'bi-twitch',
            'pinterest-downloader' => 'bi-pinterest',
        ];

        return isset($icons[$this->slug]) ? $icons[$this->slug] : null;
    }

    public function brandColor()
    {
        $colors = [
            'youtube-downloader' => '#ff0000',
            'instagram-downloader' => '#e4405f',
            'tiktok-downloader' => '#111111',
            'facebook-downloader' => '#0866ff',
            'twitter-downloader' => '#111111',
            'vimeo-downloader' => '#1ab7ea',
            'dailymotion-downloader' => '#0b73e0',
            'twitch-downloader' => '#9146ff',
            'pinterest-downloader' => '#bd081c',
        ];

        return isset($colors[$this->slug]) ? $colors[$this->slug] : '#2166f3';
    }
}

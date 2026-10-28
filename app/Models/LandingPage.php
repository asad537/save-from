<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'eyebrow', 'heading', 'intro', 'content',
        'meta_title', 'meta_description', 'faq_items', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'faq_items' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Site-wide help pages that are not tied to one platform.
     */
    const HUB_SLUGS = ['download-troubleshooting', 'how-to-download-videos'];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * The platform hub page this specialist page belongs to, if any.
     */
    public function parentPlatform()
    {
        foreach (SupportedSite::CHILD_PAGES as $platformSlug => $children) {
            if (in_array($this->slug, $children, true)) {
                return SupportedSite::active()->where('slug', $platformSlug)->first();
            }
        }

        return null;
    }

    /**
     * Other specialist pages under the same platform.
     */
    public function siblingPages()
    {
        foreach (SupportedSite::CHILD_PAGES as $children) {
            if (in_array($this->slug, $children, true)) {
                return static::active()->whereIn('slug', $children)->where('slug', '<>', $this->slug)->orderBy('sort_order')->get();
            }
        }

        return collect();
    }

    /**
     * Slug-token groups that pick the blog guides shown on each tool page.
     * A post matches when its slug contains at least one token from every group.
     */
    const GUIDE_TOKENS = [
        'youtube-shorts-downloader' => [['shorts']],
        'youtube-to-mp4' => [['youtube'], ['mp4', '4k', 'converter', 'clips', 'resolution']],
        'youtube-mp3-downloader' => [['youtube', 'audio', 'mp3'], ['mp3', 'audio']],
        'instagram-reels-downloader' => [['instagram', 'ig'], ['reels', 'reel', 'mp4']],
        'instagram-stories-downloader' => [['instagram'], ['story', 'stories']],
        'instagram-photo-downloader' => [['instagram', 'ig'], ['photo', 'photos', 'pics', 'images', 'image']],
        'tiktok-mp3-downloader' => [['tiktok'], ['mp3', 'audio']],
        'tiktok-mp4-downloader' => [['tiktok'], ['mp4', 'watermark', 'videos']],
        'facebook-reels-downloader' => [['facebook'], ['reel', 'reels']],
        'download-troubleshooting' => [['working', 'fix', '403', 'fixes', 'safety']],
        'how-to-download-videos' => [['how', 'web', 'mobile', 'link']],
    ];

    /**
     * Blog guides that belong with this tool page, newest first.
     */
    public function relatedPosts($limit = 4)
    {
        $groups = self::GUIDE_TOKENS[$this->slug] ?? [];
        if (!$groups) {
            return collect();
        }

        return BlogPost::published()->where('language', 'en')->latest('published_at')->latest('id')->get(['id', 'title', 'slug'])
            ->filter(function ($post) use ($groups) {
                $tokens = explode('-', $post->slug);
                foreach ($groups as $group) {
                    if (!array_intersect($group, $tokens)) {
                        return false;
                    }
                }
                return true;
            })->take($limit)->values();
    }

    public function isHub()
    {
        return in_array($this->slug, self::HUB_SLUGS, true);
    }
}

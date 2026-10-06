<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SupportedSite extends Model
{
    protected $fillable = [
        'name', 'slug', 'domains', 'logo_url', 'description',
        'meta_title', 'meta_description', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

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

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

    public function isHub()
    {
        return in_array($this->slug, self::HUB_SLUGS, true);
    }
}

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

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }
}

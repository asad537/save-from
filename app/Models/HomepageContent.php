<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $table = 'homepage_seo_contents';

    protected $fillable = [
        'heading', 'intro', 'content', 'meta_title', 'meta_description', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}

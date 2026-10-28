<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TrimMp4GuideDescription extends Migration
{
    public function up()
    {
        DB::table('blog_posts')->where('slug', 'mp4-downloader-save-any-public-video-as-mp4-guide')->update([
            'meta_description' => 'How an MP4 downloader works on YouTube, Instagram, TikTok, Facebook and more: which platforms return MP4, why YouTube gives WEBM above 1080p, and conversion.',
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        // Forward-only.
    }
}

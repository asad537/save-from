<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFacebookReelGuideCover extends Migration
{
    public function up()
    {
        DB::table('blog_posts')
            ->where('slug', 'facebook-reel-downloader-public-reels-mobile-guide')
            ->update([
                'featured_image' => 'images/blog/facebook-reel-public-link-guide.svg',
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        DB::table('blog_posts')
            ->where('slug', 'facebook-reel-downloader-public-reels-mobile-guide')
            ->update([
                'featured_image' => 'images/blog/facebook-public-video-guide.jpg',
                'updated_at' => now(),
            ]);
    }
}

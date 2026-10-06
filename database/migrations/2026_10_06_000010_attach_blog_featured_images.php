<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AttachBlogFeaturedImages extends Migration
{
    private $images = [
        'download-public-youtube-video-formats-quality-link-tips' => 'images/blog/youtube-public-video-guide.jpg',
        'instagram-reels-posts-stories-public-link-guide' => 'images/blog/instagram-reels-stories-guide.jpg',
        'tiktok-link-guide-mp4-mp3-short-urls' => 'images/blog/tiktok-mp4-mp3-guide.jpg',
        'facebook-video-links-public-posts-watch-privacy' => 'images/blog/facebook-public-video-guide.jpg',
        'save-video-public-x-post-correct-url' => 'images/blog/x-public-video-guide.jpg',
        'vimeo-download-guide-public-links-privacy-quality' => 'images/blog/vimeo-privacy-quality-guide.jpg',
        'dailymotion-video-url-guide-short-links-quality' => 'images/blog/dailymotion-links-quality-guide.jpg',
        'twitch-clips-vods-live-streams-public-link-guide' => 'images/blog/twitch-clips-vods-guide.jpg',
    ];

    public function up()
    {
        foreach ($this->images as $slug => $image) {
            DB::table('blog_posts')->where('slug', $slug)->update([
                'featured_image' => $image,
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', array_keys($this->images))->update([
            'featured_image' => null,
            'updated_at' => now(),
        ]);
    }
}

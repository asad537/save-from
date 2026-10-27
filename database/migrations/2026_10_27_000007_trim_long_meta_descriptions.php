<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TrimLongMetaDescriptions extends Migration
{
    public function up()
    {
        $descriptions = [
            'save-video-public-x-post-correct-url' => 'Save a video from a public X post: the right status URL, t.co and /i/status/ links, the MP4 bitrates X serves, GIFs as MP4 and protected accounts.',
            'twitch-clips-vods-live-streams-public-link-guide' => 'Twitch clip, VOD and live links explained: URL formats, how long VODs last, Clips versus Highlights, qualities and why subscriber-only VODs return nothing.',
            'facebook-reel-downloader-public-reels-mobile-guide' => 'Download public Facebook Reels on Android, iPhone or desktop: copy the right Reel link, compare the MP4 quality, find the saved file and fix expired links.',
            'dailymotion-video-downloader-public-links-quality-guide' => 'How a Dailymotion video downloader handles full and dai.ly links, what the 240p to 1080p renditions mean, embedded players and why some links fail.',
        ];
        foreach ($descriptions as $slug => $description) {
            DB::table('blog_posts')->where('slug', $slug)->update(['meta_description' => $description, 'updated_at' => now()]);
        }
    }

    public function down()
    {
        // Forward-only.
    }
}

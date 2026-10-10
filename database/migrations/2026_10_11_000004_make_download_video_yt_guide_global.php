<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeDownloadVideoYtGuideGlobal extends Migration
{
    public function up()
    {
        $post = DB::table('blog_posts')
            ->where('slug', 'download-video-yt-mobile-india-guide')
            ->first();

        if (!$post) {
            return;
        }

        DB::table('blog_posts')->where('id', $post->id)->update([
            'slug' => 'download-video-yt-mobile-guide',
            'title' => 'Download Video YT: YouTube Video Guide for Mobile',
            'excerpt' => 'A practical mobile guide to copying a public YouTube link on Android or iPhone, checking available MP4 or MP3 options and saving permitted files.',
            'content' => str_replace(
                'This guide keeps the workflow practical for viewers in India.',
                'This guide keeps the workflow practical wherever you watch on mobile.',
                $post->content
            ),
            'meta_title' => 'Download Video YT: Mobile Guide | Save-Froms',
            'meta_description' => 'Download video YT on Android or iPhone: copy a public YouTube link, compare available MP4 or MP3 options and troubleshoot common errors.',
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        $post = DB::table('blog_posts')
            ->where('slug', 'download-video-yt-mobile-guide')
            ->first();

        if (!$post) {
            return;
        }

        DB::table('blog_posts')->where('id', $post->id)->update([
            'slug' => 'download-video-yt-mobile-india-guide',
            'title' => 'Download Video YT: YouTube Video Guide for Mobile in India',
            'excerpt' => 'A simple India-focused guide to copying a public YouTube link on Android or iPhone, checking available MP4 or MP3 options and saving permitted files.',
            'content' => str_replace(
                'This guide keeps the workflow practical wherever you watch on mobile.',
                'This guide keeps the workflow practical for viewers in India.',
                $post->content
            ),
            'meta_title' => 'Download Video YT: Mobile Guide for India | Save-Froms',
            'meta_description' => 'Download video YT on Android or iPhone in India: copy a public YouTube link, compare available MP4 or MP3 options and troubleshoot common errors.',
            'updated_at' => now(),
        ]);
    }
}

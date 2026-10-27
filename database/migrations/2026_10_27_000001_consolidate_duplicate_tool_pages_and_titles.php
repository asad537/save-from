<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Live audit 2026-10-27: one page per search intent.
 *
 * - The three keyword pages that duplicated a platform hub (same H1 / same
 *   title keyword) are retired; routes/web.php 301s them to the hub.
 * - Platform hubs and specialist tools get exact-match, natural H1s and titles
 *   (no "Public", "Tool", "Available", "Check", "Use a" filler) under 60 chars.
 * - Internal links to the retired slugs are rewritten in stored content.
 * - The Portuguese guide is marked as Portuguese.
 */
class ConsolidateDuplicateToolPagesAndTitles extends Migration
{
    const RETIRED = [
        'download-vimeo-videos' => 'vimeo-downloader',
        'dailymotion-to-mp4' => 'dailymotion-downloader',
        'youtube-link-downloader' => 'youtube-downloader',
    ];

    public function up()
    {
        $now = now();

        foreach (self::RETIRED as $old => $new) {
            DB::table('landing_pages')->where('slug', $old)->update(['is_active' => false, 'updated_at' => $now]);

            foreach (['blog_posts' => 'content', 'supported_sites' => 'description', 'landing_pages' => 'content'] as $table => $column) {
                DB::table($table)->update([$column => DB::raw("REPLACE($column, 'href=\"/$old\"', 'href=\"/$new\"')")]);
                DB::table($table)->update([$column => DB::raw("REPLACE($column, 'href=\"https://save-froms.net/$old\"', 'href=\"https://save-froms.net/$new\"')")]);
            }
        }

        DB::table('blog_posts')->update(['content' => DB::raw("REPLACE(content, '>YouTube Link Downloader</a>', '>YouTube Video Downloader</a>')")]);

        foreach ($this->platforms() as $slug => $fields) {
            DB::table('supported_sites')->where('slug', $slug)->update($fields + ['updated_at' => $now]);
        }

        foreach ($this->tools() as $slug => $fields) {
            DB::table('landing_pages')->where('slug', $slug)->update($fields + ['updated_at' => $now]);
        }

        DB::table('blog_posts')->where('slug', 'baixar-video-instagram-links-publicos-guia')->update(['language' => 'pt', 'updated_at' => $now]);
    }

    private function platforms()
    {
        return [
            'youtube-downloader' => [
                'heading' => '[YouTube] Video Downloader',
                'meta_title' => 'YouTube Video Downloader – Free MP4, WEBM & MP3 | Save-Froms',
            ],
            'instagram-downloader' => [
                'heading' => '[Instagram] Video Downloader',
                'meta_title' => 'Instagram Video Downloader – Reels & Posts MP4 | Save-Froms',
            ],
            'facebook-downloader' => [
                'heading' => '[Facebook] Video Downloader',
                'meta_title' => 'Facebook Video Downloader – Save Videos as MP4 | Save-Froms',
            ],
            'twitter-downloader' => [
                'heading' => '[Twitter / X] Video Downloader',
                'meta_title' => 'Twitter / X Video Downloader – Save Videos as MP4 | Save-Froms',
            ],
            'vimeo-downloader' => [
                'heading' => '[Vimeo] Video Downloader',
                'meta_title' => 'Vimeo Video Downloader – Save Vimeo Videos as MP4 | Save-Froms',
            ],
            'dailymotion-downloader' => [
                'heading' => '[Dailymotion] Video Downloader',
                'meta_title' => 'Dailymotion Video Downloader – Save Videos as MP4 | Save-Froms',
            ],
        ];
    }

    private function tools()
    {
        return [
            'youtube-shorts-downloader' => [
                'title' => 'YouTube Shorts Downloader',
                'heading' => '[YouTube Shorts] Downloader',
                'meta_title' => 'YouTube Shorts Downloader – Save Shorts as MP4 | Save-Froms',
            ],
            'youtube-to-mp4' => [
                'title' => 'YouTube to MP4 Downloader',
                'heading' => '[YouTube to MP4] Downloader',
                'meta_title' => 'YouTube to MP4 Downloader – 1080p, 720p & 360p | Save-Froms',
            ],
            'youtube-mp3-downloader' => [
                'title' => 'YouTube to MP3 Downloader',
                'heading' => 'YouTube to [MP3] Downloader',
                'meta_title' => 'YouTube to MP3 Downloader – Save Audio Online | Save-Froms',
            ],
            'instagram-reels-downloader' => [
                'title' => 'Instagram Reels Downloader',
                'heading' => '[Instagram Reels] Downloader',
                'meta_title' => 'Instagram Reels Downloader – Save Reels as MP4 | Save-Froms',
                'meta_description' => 'Download public Instagram Reels as MP4. Paste one Reel link, compare the available quality and file size, and save it on Android, iPhone or desktop.',
            ],
            'instagram-stories-downloader' => [
                'title' => 'Instagram Stories Downloader',
                'heading' => '[Instagram Stories] Downloader',
                'meta_title' => 'Instagram Stories Downloader – Story to MP4 | Save-Froms',
            ],
            'tiktok-mp3-downloader' => [
                'title' => 'TikTok MP3 Downloader',
                'heading' => '[TikTok MP3] Downloader',
                'meta_title' => 'TikTok MP3 Downloader – Save TikTok Audio Online | Save-Froms',
            ],
            'tiktok-mp4-downloader' => [
                'title' => 'TikTok MP4 Downloader',
                'heading' => '[TikTok MP4] Downloader',
                'meta_title' => 'TikTok MP4 Downloader – Save TikTok Videos Online | Save-Froms',
            ],
            'facebook-reels-downloader' => [
                'title' => 'Facebook Reels Downloader',
                'heading' => '[Facebook Reels] Downloader',
                'meta_title' => 'Facebook Reels Downloader – Save Reels as MP4 | Save-Froms',
                'meta_description' => 'Download public Facebook Reels as MP4. Paste one Reel link, check the available quality and file size, and save it on Android, iPhone or desktop.',
            ],
        ];
    }

    public function down()
    {
        $now = now();

        foreach (array_keys(self::RETIRED) as $slug) {
            DB::table('landing_pages')->where('slug', $slug)->update(['is_active' => true, 'updated_at' => $now]);
        }

        DB::table('blog_posts')->where('slug', 'baixar-video-instagram-links-publicos-guia')->update(['language' => 'en', 'updated_at' => $now]);
        // Title and heading changes are intentionally retained.
    }
}

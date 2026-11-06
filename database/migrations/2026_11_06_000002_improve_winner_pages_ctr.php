<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * CTR + funnel fix for three GSC "winner" pages (2026-11-06), following
 * the same treatment applied to the Instagram Reels page.
 *
 * GSC (last ~30 days) data behind each change:
 *  - savefrom-not-working-link-preparing-403-fixes : 319 impr, pos ~11.6,
 *    but ZERO tool links in the body. A "savefrom not working" searcher
 *    wants something that works NOW -> add a prominent CTA to the free
 *    Save-Froms downloader (honest, nominative, not-affiliated), and
 *    sharpen the title/meta to promise a working fix + alternative.
 *  - savefrom-net-alternative-browser-workflow-2026 : 108 impr, pos ~15.3,
 *    also ZERO tool links -> add the same funnel CTA + value-prop meta.
 *  - video-resolution-vs-file-size-360p-720p-1080p : 173 impr, pos ~25.8,
 *    CTR only 0.58% -> keyword-forward title ("360p vs 720p vs 1080p",
 *    matching the real queries) + a soft CTA to the downloader.
 *
 * Each CTA is prepended once (guarded by strpos so re-running is safe).
 * The informative bodies are left intact for SEO depth. The homepage is
 * deliberately NOT touched: its title/meta are already strong; its only
 * issue is ranking position, an authority problem, not a CTR one.
 */
class ImproveWinnerPagesCtr extends Migration
{
    public function up()
    {
        foreach ($this->items() as $it) {
            $row = DB::table('blog_posts')->where('slug', $it['slug'])->first();
            if (! $row) {
                continue;
            }
            $content = $row->content;
            if (strpos($content, 'data-sf-cta') === false) {
                $content = $it['cta'] . "\n" . $content;
            }
            DB::table('blog_posts')->where('slug', $it['slug'])->update(array_merge([
                'content' => $content,
                'updated_at' => now(),
            ], $it['set']));
        }
    }

    public function down()
    {
        foreach ($this->items() as $it) {
            $row = DB::table('blog_posts')->where('slug', $it['slug'])->first();
            if (! $row) {
                continue;
            }
            $content = str_replace($it['cta'] . "\n", '', $row->content);
            DB::table('blog_posts')->where('slug', $it['slug'])->update(array_merge([
                'content' => $content,
                'updated_at' => now(),
            ], $it['restore']));
        }
    }

    private function items()
    {
        return [
            [
                'slug' => 'savefrom-not-working-link-preparing-403-fixes',
                'cta' => '<div class="note" data-sf-cta><strong>Need it working now?</strong> Paste your video link into the <a href="/">free Save-Froms downloader</a> — it runs in your browser with no app and no sign-up, and supports public YouTube, Instagram, TikTok and Facebook links as MP4 or MP3. The fixes below explain why a SaveFrom-style download shows 403, an endless &ldquo;Preparing&rdquo; state or a link error. <em>Save-Froms is an independent tool and is not affiliated with SaveFrom.net.</em></div>',
                'set' => [
                    'title' => "SaveFrom Not Working? 403, 'Preparing' and Link Error Fixes",
                    'meta_title' => 'SaveFrom Not Working? 403, Preparing & Link Error Fixes',
                    'meta_description' => "SaveFrom stuck on 'Preparing', showing a 403 or an unsupported-link error? Fix the common causes — or paste your link into our free browser downloader that works right now.",
                    'excerpt' => "SaveFrom stuck on 'Preparing', 403 or an unsupported link? Here are the real causes and fixes — plus a free browser downloader you can use right now if you just need the video.",
                ],
                'restore' => [
                    'title' => 'SaveFrom Not Working: 403, Preparing and Link Errors Explained',
                    'meta_title' => 'SaveFrom Not Working: 403, Preparing & Link Errors',
                    'meta_description' => 'Fix common SaveFrom downloader problems: unsupported links, endless Preparing, expired files, 403 errors, mobile browser blocks and private media.',
                    'excerpt' => 'Understand why a SaveFrom-style downloader shows errors like 403, endless Preparing or unsupported link, and how to fix each one on mobile and desktop.',
                ],
            ],
            [
                'slug' => 'savefrom-net-alternative-browser-workflow-2026',
                'cta' => '<div class="note" data-sf-cta><strong>Try it now:</strong> paste a public video link into the <a href="/">free Save-Froms downloader</a> — browser-based, no app or sign-up, with MP4 and MP3 options for YouTube, Instagram, TikTok and Facebook. The guide below covers exactly what to look for in a SaveFrom.net alternative. <em>Save-Froms is an independent tool and is not affiliated with SaveFrom.net.</em></div>',
                'set' => [
                    'meta_description' => 'Looking for a SaveFrom.net alternative? Use our free browser downloader — no app or sign-up, with MP4 and MP3 for public YouTube, Instagram, TikTok and Facebook links.',
                ],
                'restore' => [
                    'meta_description' => 'Compare what to look for in a SaveFrom.net alternative: public-link support, MP4 and MP3 options, mobile use, clear buttons and responsible downloading.',
                ],
            ],
            [
                'slug' => 'video-resolution-vs-file-size-360p-720p-1080p',
                'cta' => '<div class="note" data-sf-cta><strong>Ready to download?</strong> Once you have picked a resolution, paste your link into the <a href="/">free Save-Froms downloader</a> and choose that quality — no app or sign-up. The guide below explains how 360p, 720p and 1080p differ in clarity and file size so you pick the right one.</div>',
                'set' => [
                    'title' => '360p vs 720p vs 1080p: Video Quality and File Size Explained',
                ],
                'restore' => [
                    'title' => 'Video Resolution vs File Size: 360p, 720p and 1080p Explained',
                ],
            ],
        ];
    }
}

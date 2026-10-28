<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "tiktok downloader hd" cluster (UK: 4,400 + 1,000 + 880 + 880 + 720 a month).
 * A tool page that says exactly what "HD" means on TikTok (the 1080p rendition
 * that exists only when the creator uploaded in HD) instead of promising 4K.
 * Also links the new page from the TikTok hub, the MP4 page and the
 * watermark guide.
 */
class AddTiktokHdDownloaderPage extends Migration
{
    public function up()
    {
        $now = now();
        DB::table('landing_pages')->updateOrInsert(['slug' => 'tiktok-hd-downloader'], [
            'title' => 'TikTok HD Downloader',
            'eyebrow' => 'TikTok HD Video',
            'heading' => '[TikTok HD] Downloader',
            'intro' => 'Paste a public TikTok link and download the HD rendition, 1080p when the creator uploaded in HD, 720p otherwise, as a clean MP4. No app, no watermark overlay, no login.',
            'meta_title' => 'TikTok HD Downloader – 1080p MP4, No Watermark | Save-Froms',
            'meta_description' => 'Download TikTok videos in HD: the 1080p MP4 TikTok serves when the creator uploaded in HD, 720p otherwise, with no watermark overlay. See which row is HD.',
            'content' => $this->content(),
            'faq_items' => json_encode([
                ['question' => 'Why is my TikTok download 720p, not HD?', 'answer' => 'The creator uploaded with "Allow high-quality uploads" off, or from a 720p source. TikTok then stores only a 720p rendition and no downloader can return more.'],
                ['question' => 'Can I download TikTok videos in 4K?', 'answer' => 'No. TikTok re-encodes every upload to at most 1080 × 1920. Sites that advertise 4K TikTok downloads deliver upscaled or mislabelled files.'],
                ['question' => 'Which row is the HD one?', 'answer' => 'The MP4 row labelled 1080P. When two rows share a resolution, the larger file is the higher bitrate and looks sharper in motion.'],
                ['question' => 'Does HD mean without the watermark?', 'answer' => 'They are separate things. The rows come from the web-player rendition, which usually has no overlay, at whatever resolution TikTok stores.'],
                ['question' => 'How big is an HD TikTok?', 'answer' => 'Roughly 19 to 38 MB per minute at 1080p, 11 to 19 MB at 720p.'],
                ['question' => 'Does it work with vm.tiktok.com links?', 'answer' => 'Yes. Short share links redirect to the full video page before the check runs.'],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'is_active' => 1,
            'sort_order' => 15,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach (['tiktok-mp4-downloader' => 'landing_pages', 'tiktok-downloader' => 'supported_sites'] as $slug => $table) {
            $column = $table === 'supported_sites' ? 'description' : 'content';
            $row = DB::table($table)->where('slug', $slug)->first();
            if ($row && strpos($row->$column, '/tiktok-hd-downloader') === false) {
                $note = $table === 'supported_sites'
                    ? '<p>Looking specifically for the sharpest file? The <a href="/tiktok-hd-downloader">TikTok HD downloader</a> page explains which row is the 1080p rendition and why some clips only have 720p.</p>'
                    : '<p>For the HD-specific questions, including why a clip has no 1080p row and what the HD toggle in TikTok\'s upload screen changes, see the <a href="/tiktok-hd-downloader">TikTok HD downloader</a>.</p>';
                DB::table($table)->where('id', $row->id)->update([$column => $row->$column."\n".$note, 'updated_at' => $now]);
            }
        }

        $guide = DB::table('blog_posts')->where('slug', 'download-tiktok-videos-without-watermark-public-link-guide')->first();
        if ($guide && strpos($guide->content, '/tiktok-hd-downloader') === false) {
            $content = str_replace('<h2>Frequently asked questions</h2>', '<p>If the sharper file matters more than the overlay, the <a href="/tiktok-hd-downloader">TikTok HD downloader</a> page covers the 1080p rendition and the upload setting that controls it.</p>'."\n".'<h2>Frequently asked questions</h2>', $guide->content);
            DB::table('blog_posts')->where('id', $guide->id)->update(['content' => $content, 'updated_at' => $now]);
        }
    }

    private function content(): string
    {
        return <<<'HTML'
<h2>What "HD" means on TikTok</h2>
<p>TikTok stores every clip at a handful of fixed renditions and never above 1080 × 1920. "HD" on TikTok therefore means the 1080p rendition, and it exists only when two things are true: the creator switched on <strong>Allow high-quality uploads</strong> (TikTok's HD toggle on the posting screen) and the source footage was at least 1080p. When either is missing, TikTok keeps a 720p copy and nothing else, so no downloader, app or "HD converter" can return more. A <strong>TikTok HD downloader</strong> is simply a tool that shows you the 1080p row when it exists and tells you honestly when it does not.</p>
<div class="note"><strong>Public clips, permitted downloads:</strong> Save-Froms reads public TikTok links without an account. Keep clips you made or ones whose creator allows saving; the HD file is still their work.</div>
<h2>The renditions TikTok actually serves</h2>
<table><thead><tr><th>Row label</th><th>Pixels</th><th>Bitrate</th><th>One minute</th><th>When it exists</th></tr></thead><tbody>
<tr><td>540P</td><td>540 × 960</td><td>1 to 1.5 Mbps</td><td>8 to 11 MB</td><td>Older uploads, some regions</td></tr>
<tr><td>720P</td><td>720 × 1280</td><td>1.5 to 2.5 Mbps</td><td>11 to 19 MB</td><td>Every clip</td></tr>
<tr><td>1080P (HD)</td><td>1080 × 1920</td><td>2.5 to 5 Mbps</td><td>19 to 38 MB</td><td>HD toggle on and a 1080p source</td></tr>
<tr><td>1080P60 (HD, 60 fps)</td><td>1080 × 1920</td><td>4 to 8 Mbps</td><td>30 to 60 MB</td><td>HD toggle on and 60 fps footage</td></tr>
</tbody></table>
<p>There is no 1440p or 2160p rendition on TikTok, which is why "download TikTok in 4K" sites return a 1080p file with a 4K name, or an upscaled copy that is larger but not sharper.</p>
<h2>Download a TikTok in HD</h2>
<ol class="sf-steps">
<li><strong>Copy the link</strong><span>Share → Copy link in the app, or the address bar on tiktok.com. Short vm/vt links are fine.</span></li>
<li><strong>Paste it above</strong><span>Use Chrome or Safari, not the browser inside the TikTok app.</span></li>
<li><strong>Find the 1080P row</strong><span>If it is there, that is HD. If the list stops at 720P, the clip was never uploaded in HD.</span></li>
<li><strong>Choose by size when two rows match</strong><span>Two 1080P rows are two bitrates; the larger keeps more detail in fast motion.</span></li>
<li><strong>Save</strong><span>Downloads on Android and desktop; Files → Downloads on iPhone, then Save Video to Photos.</span></li>
</ol>
<h2>How to tell before downloading whether a clip has HD</h2>
<p>In the TikTok app, tap and hold the video (or open the three-dot menu) and look for the <strong>HD</strong> badge in the quality option; the website shows a small "HD" tag on the player for the same clips. If the option offers only "Auto" and "Data saver", the clip has no 1080p rendition. Creators who post from a phone with the toggle off, who repost downloaded clips, or who upload from TikTok's own editor at the default setting all produce 720p-only videos, which covers most of the For You feed.</p>
<h2>HD and the watermark are two different things</h2>
<p>The rows come from TikTok's web-player rendition, which carries no moving username overlay at any resolution; the <a href="/blog/download-tiktok-videos-without-watermark-public-link-guide">watermark guide</a> explains when a clip is served only with the overlay. HD is about pixels and bitrate. A clip can be HD with a watermark (saved through the app's own button) or 720p without one (downloaded here from a 720p upload). Choosing the 1080P row here gives you both when the clip has HD.</p>
<h2>Why the HD file still looks softer than the app</h2>
<p>The TikTok app sharpens during playback and matches the rendition to your screen, so a 1080p clip on a phone can look crisper in the app than the same file in a gallery viewer. The downloaded MP4 is the stored rendition without that processing. On a laptop or TV the difference disappears, and for editing the file is exactly what TikTok holds. If a 1080p file looks blurry everywhere, the source was soft before upload: a screen recording, a zoomed crop or a reposted download, none of which the HD toggle can fix.</p>
<h2>Getting HD for your own TikToks</h2>
<p>For future posts, switch on Allow high-quality uploads under "More options" on the posting screen and shoot at 1080p or higher. For clips already posted at 720p, the original is still in your camera roll or drafts at full quality; re-uploading it with the toggle on creates a new post with the 1080p rendition, while the old post stays at 720p. Downloading your own HD post here returns the same 1080p file anyone else would get, which is useful when the original has been deleted from the phone.</p>
<h2>HD on Android, iPhone and desktop</h2>
<p>The rows are identical on every device; the file size is what changes the experience. A one-minute 1080p60 clip is up to 60 MB, so use Wi-Fi on a phone and expect the download to take a few seconds longer than 720p. Android saves to Downloads, iPhone to Files → Downloads, desktop browsers to Downloads. Messaging apps compress whatever you send, so an HD clip shared through WhatsApp arrives at roughly 720p; send it as a document to keep the quality, or share the TikTok link instead.</p>
<h2>When no HD row appears</h2>
<ul>
<li><strong>720P is the highest row:</strong> the clip was uploaded without HD. This is the common case and not an error.</li>
<li><strong>Only one row and it is small:</strong> an old clip stored at 540p, or a region where TikTok serves a reduced ladder.</li>
<li><strong>No rows at all:</strong> private, Friends-only, deleted or age-restricted clip; or a profile, sound or hashtag link instead of one video.</li>
<li><strong>Photo-mode post:</strong> images plus a sound; there is no video rendition, HD or otherwise.</li>
<li><strong>Just posted:</strong> TikTok generates the 1080p rendition after the 720p one; wait a few minutes and paste again.</li>
</ul>
HTML;
    }

    public function down()
    {
        DB::table('landing_pages')->where('slug', 'tiktok-hd-downloader')->delete();
    }
}

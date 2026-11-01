<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "download youtube thumbnail" / "youtube
 * thumbnail hd / maxresdefault / full size" (2026-11-01). A different
 * content type (image, not video) that our YouTube cluster never
 * covered, with the honest angle: the max is 1280x720 maxresdefault
 * and not every video has it.
 */
class PublishYoutubeThumbnailGuide extends Migration
{
    public function up()
    {
        $now = now();
        foreach ($this->posts() as $post) {
            DB::table('blog_posts')->updateOrInsert(['slug' => $post['slug']], $post + ['language' => 'en', 'status' => 'published', 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    private function posts()
    {
        return [
            [
                'title' => 'Download a YouTube Thumbnail in HD (maxresdefault and the Real Limits)',
                'slug' => 'youtube-thumbnail-download-hd-maxresdefault-guide',
                'excerpt' => 'A YouTube thumbnail is an image, not a video, and the biggest version is 1280x720 maxresdefault — when the video has it. Here is how to grab the full-size thumbnail from a link, the manual URL trick, and why some videos top out smaller.',
                'content' => <<<'HTML'
<p><strong>A YouTube thumbnail is just an image</strong> — the cover picture you see before a video plays — so saving one has nothing to do with downloading the video itself. The highest-quality version is <code>maxresdefault.jpg</code> at 1280x720, but here is the catch most tools skip: YouTube only creates that large file when the video is HD or the creator uploaded a high-resolution thumbnail. Older or small uploads top out lower. This guide shows how to grab the full-size thumbnail from a video link, the manual address trick that needs no tool at all, and what the real size limits are.</p>
<div class="note"><strong>Use with permission:</strong> A thumbnail is the creator's image. Saving one for reference, a mock-up, or your own video is one thing; reusing someone else's thumbnail publicly without permission is a copyright issue. Keep this to thumbnails you own or are allowed to use.</div>
<h2>Grab the thumbnail, step by step</h2>
<ol>
<li>Open the video on YouTube and copy its link — <code>youtube.com/watch?v=VIDEO_ID</code> or <code>youtu.be/VIDEO_ID</code>.</li>
<li>Paste it into a thumbnail tool, or build the image address yourself (next section). The full-size image opens.</li>
<li>On desktop, right-click the image and choose <strong>Save image as</strong>. On a phone, long-press it and choose <strong>Download image</strong>.</li>
<li>The JPG lands in your Downloads folder, ready to use.</li>
</ol>
<h2>The manual trick: build the address yourself</h2>
<p>Every thumbnail lives at a fixed address made from the video ID, so once you have the ID you can open the image directly with no tool. Take the ID from the link — in <code>youtu.be/ABC123xyz</code> the ID is <code>ABC123xyz</code> — and drop it into this pattern:</p>
<p><code>https://img.youtube.com/vi/VIDEO_ID/maxresdefault.jpg</code></p>
<p>Open that in your browser and, if the video has an HD thumbnail, the 1280x720 image appears to save. If you get a smaller or blank image, that video simply does not have the max-resolution file, and you step down to the next size.</p>
<h2>The sizes YouTube actually stores</h2>
<p>YouTube keeps several fixed sizes for each video. The largest you can rely on everywhere is <code>hqdefault</code>; <code>maxresdefault</code> is bigger but only exists for HD or custom-thumbnail uploads.</p>
<table><thead><tr><th>File name</th><th>Size</th><th>Always available?</th></tr></thead><tbody>
<tr><td>maxresdefault.jpg</td><td>1280x720</td><td>Only if the video is HD / custom thumbnail</td></tr>
<tr><td>sddefault.jpg</td><td>640x480</td><td>Usually, not always</td></tr>
<tr><td>hqdefault.jpg</td><td>480x360</td><td>Yes, for every video</td></tr>
<tr><td>mqdefault.jpg</td><td>320x180</td><td>Yes</td></tr>
</tbody></table>
<p>So 1280x720 is the ceiling, not a guarantee. No tool can "upscale" a thumbnail to a size YouTube never made — a page that promises 4K thumbnails is stretching a 1280-wide image, which only looks softer.</p>
<h2>Thumbnail vs the video itself</h2>
<p>Saving the thumbnail is a quick image grab; downloading the video is a separate job with its own quality choices. If you actually want the video, use the <a href="/youtube-downloader">YouTube downloader</a>, and the <a href="/blog/download-public-youtube-video-formats-quality-link-tips">YouTube video URLs guide</a> explains which link types work. For how resolution maps to file size in general, see <a href="/blog/video-resolution-vs-file-size-360p-720p-1080p">video resolution vs file size</a>.</p>
<h2>Why creators grab thumbnails</h2>
<p>Most people saving thumbnails are studying what works — comparing styles in a niche before designing their own, building a reference board, or checking how a competitor frames a face and title. That is fine as research. The line is reuse: putting someone else's thumbnail on your own public video is a copyright problem, not a shortcut. Use what you save to inform your design, not to copy it outright.</p>
<h2>Thumbnail not saving? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>maxresdefault is blank</td><td>The video has no HD thumbnail</td><td>Use hqdefault.jpg instead</td></tr>
<tr><td>Image looks soft / stretched</td><td>A tool upscaled a smaller file</td><td>Take the real size; it cannot go higher than the source</td></tr>
<tr><td>Wrong video's thumbnail</td><td>You copied a playlist or channel link</td><td>Open the single video and copy its own link</td></tr>
<tr><td>Can't right-click on mobile</td><td>Phones use long-press</td><td>Press and hold the image, then Download</td></tr>
<tr><td>Nothing opens</td><td>The video ID was wrong</td><td>Recheck the ID from the watch or youtu.be link</td></tr>
</tbody></table>
<h2>Frequently asked questions</h2>
<h3>What is the biggest YouTube thumbnail size?</h3><p>1280x720, stored as <code>maxresdefault.jpg</code>. It only exists when the video is HD or the creator uploaded a high-resolution thumbnail; otherwise the top is usually 640x480 or 480x360.</p>
<h3>Can I get a 4K thumbnail?</h3><p>No. YouTube does not store thumbnails above 1280x720, so any "4K thumbnail" is just an upscaled image that looks softer, not sharper.</p>
<h3>Do I need a tool, or can I do it manually?</h3><p>Either. Open <code>img.youtube.com/vi/VIDEO_ID/maxresdefault.jpg</code> in your browser and save the image, or paste the video link into a thumbnail tool.</p>
<h3>Is it legal to download a thumbnail?</h3><p>Saving one for reference is generally fine; reusing someone else's thumbnail on your own public content without permission is a copyright issue.</p>
<p><small>Guide published: November 1, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-youtube-thumbnail-download-hd-maxresdefault-guide.svg',
                'meta_title' => 'Download YouTube Thumbnail in HD (maxresdefault, Real Limits)',
                'meta_description' => 'Download a YouTube thumbnail in HD: grab the 1280x720 maxresdefault from a video link or build the image URL by hand. Learn why some videos top out smaller and why 4K thumbnails are not real.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['youtube-thumbnail-download-hd-maxresdefault-guide'])->delete();
    }
}

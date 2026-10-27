<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Provider test 2026-10-27: public Pinterest video Pins and Threads video posts
 * return a 720p MP4 (plus the cover image for Pins); Reddit posts fail
 * ("reddit challenge parse failed"), so Reddit stays off.
 * Pinterest page is re-activated with new sections; Threads page is added.
 */
class EnablePinterestAndAddThreads extends Migration
{
    public function up()
    {
        $now = now();

        $pinterest = DB::table('supported_sites')->where('slug', 'pinterest-downloader')->first();
        if ($pinterest) {
            $description = $pinterest->description;
            if (strpos($description, 'Pinterest link anatomy') === false) {
                $pos = strpos($description, '<h2>Frequently asked questions</h2>');
                $description = $pos === false ? $description.$this->pinterestSections() : substr($description, 0, $pos).$this->pinterestSections().substr($description, $pos);
            }
            DB::table('supported_sites')->where('id', $pinterest->id)->update([
                'is_active' => 1,
                'heading' => '[Pinterest] Video Downloader',
                'meta_title' => 'Pinterest Video Downloader – Save Pins as MP4 | Save-Froms',
                'meta_description' => 'Download public Pinterest video Pins as MP4 from a pinterest.com or pin.it link. See the quality and size returned, plus the cover image, on phone or desktop.',
                'description' => $description,
                'sort_order' => 9,
                'updated_at' => $now,
            ]);
        }

        DB::table('supported_sites')->updateOrInsert(['slug' => 'threads-downloader'], [
            'name' => 'Threads',
            'domains' => 'threads.net,threads.com',
            'logo_url' => 'https://cdn.simpleicons.org/threads/000000',
            'heading' => '[Threads] Video Downloader',
            'meta_title' => 'Threads Video Downloader – Save Videos as MP4 | Save-Froms',
            'meta_description' => 'Download public Threads videos as MP4 from a threads.net or threads.com post link. See the quality and size returned before saving, on Android, iPhone or desktop.',
            'description' => $this->threadsDescription(),
            'is_active' => 1,
            'sort_order' => 10,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function pinterestSections(): string
    {
        return <<<'HTML'
<h2>Pinterest link anatomy</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Works?</th></tr></thead><tbody>
<tr><td><code>pinterest.com/pin/397372367143931632/</code></td><td>One Pin; the long number is the Pin ID</td><td>Yes</td></tr>
<tr><td><code>uk.pinterest.com/pin/…</code>, <code>in.pinterest.com/pin/…</code></td><td>The same Pin on a country subdomain</td><td>Yes; the ID is what counts</td></tr>
<tr><td><code>pin.it/4aBcDeF</code></td><td>Short link from the app's Share menu</td><td>Yes; it redirects to the Pin</td></tr>
<tr><td><code>pinterest.com/username/boardname/</code></td><td>A board</td><td>No; open one Pin from it</td></tr>
<tr><td><code>pinterest.com/search/pins/?q=…</code></td><td>Search results</td><td>No</td></tr>
<tr><td><code>pinterest.com/ideas/…</code></td><td>Topic pages</td><td>No</td></tr>
</tbody></table>
<h2>Video Pins, Idea Pins and image Pins</h2>
<p>Pinterest serves three kinds of Pin. A <strong>video Pin</strong> returns an MP4, normally 720p, plus its cover image as a separate row. An <strong>Idea Pin</strong> (the multi-page format) can contain several video and image pages; the result lists the media Pinterest exposes for it, which is often the first video only. An <strong>image Pin</strong> has no video, so the result shows only the picture row, which you can save at its original size. Pins that link out to YouTube, TikTok or a shop page are previews: the video lives on the other site, and that site's own page on Save-Froms is the one to use.</p>
<h2>What quality Pinterest returns</h2>
<p>Pinterest re-encodes uploads to a handful of renditions and serves 720p to the web player, so the MP4 row is usually 720 × 1280 for vertical Pins and 1280 × 720 for landscape ones, at a modest bitrate. A one-minute video Pin is roughly 5 to 10 MB. There is no 1080p rendition on the public player, so the 720p row is the best available; the creator's original upload is the only sharper copy.</p>
<h2>Download a video Pin on Android or iPhone</h2>
<ol class="sf-steps">
<li><strong>Open the Pin</strong><span>Tap the Pin so it fills the screen, not a board or feed.</span></li>
<li><strong>Copy the link</strong><span>Tap the share icon and choose Copy link; the app gives you a pin.it address.</span></li>
<li><strong>Paste it above</strong><span>Use Chrome or Safari, not the browser inside the Pinterest app.</span></li>
<li><strong>Choose the MP4 row</strong><span>The picture row is the cover image; the video row is the clip.</span></li>
<li><strong>Find the file</strong><span>Downloads on Android; Files → Downloads on iPhone, then Save Video to Photos.</span></li>
</ol>
<h2>Why a Pin returns only an image</h2>
<ul>
<li><strong>It is an image Pin:</strong> there was never a video.</li>
<li><strong>It is a link Pin:</strong> the video plays on the destination website, not on Pinterest.</li>
<li><strong>It is a secret-board Pin:</strong> the Pin page is not public, so the result is empty rather than an image.</li>
<li><strong>It is a shopping Pin:</strong> product Pins carry images and a shop link, not a downloadable video.</li>
</ul>
HTML;
    }

    private function threadsDescription(): string
    {
        return <<<'HTML'
<h2>What can Save-Froms download from a Threads link?</h2>
<p>Threads is Meta's text-first app, but a large share of popular posts carry a video. Save-Froms reads one public Threads post link and returns the MP4 the post contains, usually at 720p, so you can keep a clip you made or one the creator allows you to save. Text-only posts and photo posts have no video, and the result says so.</p>
<div class="sf-facts"><div><strong>Input</strong><span>A public threads.net or threads.com post link</span></div><div><strong>Output</strong><span>The post's MP4 video when it has one</span></div><div><strong>Devices</strong><span>Android, iPhone, tablets, Windows and macOS</span></div><div><strong>Installation</strong><span>No app or extension</span></div></div>
<div class="sf-notice"><strong>Public posts only:</strong> Threads profiles can be private. A private profile's posts, a deleted post and a post hidden by the author's settings return nothing, and no login is ever used. Save only videos you own or have permission to keep.</div>
<h2>Threads link anatomy</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Works?</th></tr></thead><tbody>
<tr><td><code>threads.net/@username/post/De2ct9AiRkL</code></td><td>One post; the code after /post/ is the post ID</td><td>Yes</td></tr>
<tr><td><code>threads.com/@username/post/De2ct9AiRkL</code></td><td>The same post on the newer domain</td><td>Yes</td></tr>
<tr><td><code>threads.net/t/De2ct9AiRkL</code></td><td>Short form used by some share menus</td><td>Yes; it redirects to the post</td></tr>
<tr><td><code>threads.net/@username</code></td><td>A profile</td><td>No; open one post</td></tr>
<tr><td><code>threads.net/search?q=…</code></td><td>Search</td><td>No</td></tr>
</tbody></table>
<p>The post ID is the same one Instagram uses for a cross-posted Reel, which is why a Threads post ID and its Instagram twin often look alike. They are still separate posts with separate privacy settings.</p>
<h2>How to download a Threads video</h2>
<ol class="sf-steps">
<li><strong>Open the post</strong><span>Tap the post so it opens on its own, not the feed or the profile.</span></li>
<li><strong>Copy the link</strong><span>Tap the share icon under the post and choose Copy link.</span></li>
<li><strong>Paste it above</strong><span>Use Chrome or Safari; the browser inside the Threads app blocks downloads.</span></li>
<li><strong>Pick the MP4 row</strong><span>Check the size, then press Download.</span></li>
<li><strong>Find the file</strong><span>Downloads on Android and desktop; Files → Downloads on iPhone.</span></li>
</ol>
<h2>Replies, quotes and carousels</h2>
<p>A reply is its own post with its own link, so a video inside a reply needs that reply's link, not the parent's. A quote post embeds another post: if the video belongs to the quoted post, open that post and copy its link. A post can carry several videos and photos in a carousel; the result lists each video as a separate row, in order. Reposts point at the original, and the original's link is the one to use.</p>
<h2>Quality and size</h2>
<p>Threads stores one public rendition per video, typically 720 × 1280 for vertical clips and 1280 × 720 for landscape, at 1.5 to 3 Mbps. A 30-second clip is about 5 to 10 MB; a three-minute clip around 30 to 60 MB. There is no quality menu because Threads serves a single rendition; the creator's original upload is the only sharper copy, and Instagram's cross-posted Reel sometimes exists at 1080p when the Threads copy does not.</p>
<h2>Threads-specific troubleshooting</h2>
<table><thead><tr><th>What you see</th><th>Likely cause</th><th>What to do</th></tr></thead><tbody>
<tr><td>No media found</td><td>The post is text or photos only</td><td>Nothing to download; there is no video in it</td></tr>
<tr><td>Empty result for a video post</td><td>The profile is private, or the post was deleted</td><td>Open the link in a private window: a login wall means it is not public</td></tr>
<tr><td>Result shows a different video</td><td>The link was for the parent post, not the reply</td><td>Copy the link from the exact post</td></tr>
<tr><td>Download fails after a minute</td><td>The temporary file address expired</td><td>Paste the post link again and choose the row again</td></tr>
<tr><td>Link opens the Threads app and nothing copies</td><td>The share sheet handed off to the app</td><td>Long-press the link and choose Copy, or open threads.net in a browser and copy the address bar</td></tr>
</tbody></table>
<h2>Saving your own Threads videos</h2>
<p>Threads has no built-in download button for videos, even on your own posts, and the original clip is often gone from your phone by the time you want it back. Paste your own post's link above to get the 720p rendition Threads keeps. For a sharper copy, check whether the clip also went to Instagram as a Reel, where the <a href="/instagram-reels-downloader">Instagram Reels Downloader</a> may return 1080p, or restore the original from your phone's recently deleted folder or cloud backup.</p>
<h2>Videos shared from other apps</h2>
<p>A Threads post that shows a YouTube, TikTok or news-site preview card is a link, not an uploaded video, so there is nothing for this page to return. Tap the card, open the video on its own platform, and use that platform's page on Save-Froms instead.</p>
<h2>Threads on desktop</h2>
<p>On threads.net in a desktop browser, a post opens on its own page and the address bar shows the full <code>/@username/post/ID</code> link, which you can copy directly. This is the quickest route when you are saving several of your own posts: open each, copy, paste, download, and the files land in the browser's Downloads folder.</p>
<h2>Frequently asked questions</h2>
<h3>Can I download a video from a private Threads profile?</h3><p>No. The check uses no account, so only public posts can be read.</p>
<h3>Does the creator see that I downloaded their video?</h3><p>No. Nothing is recorded on the post; Threads shows views, not downloads.</p>
<h3>Why is the Threads video 720p when the Reel is 1080p?</h3><p>Threads serves one 720p rendition. If the same clip is on Instagram as a Reel, the Instagram Reels page may return 1080p.</p>
<h3>Can I download a photo post?</h3><p>This page is for videos. A photo-only post returns no video row.</p>
<h3>Can I reuse a Threads video I downloaded?</h3><p>Only your own videos or ones whose creator has given permission. A public post does not grant reuse rights.</p>
HTML;
    }

    public function down()
    {
        DB::table('supported_sites')->where('slug', 'pinterest-downloader')->update(['is_active' => 0, 'updated_at' => now()]);
        DB::table('supported_sites')->where('slug', 'threads-downloader')->delete();
    }
}

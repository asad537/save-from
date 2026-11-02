<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "download threads video" / "threads video
 * downloader" (2026-11-02). Clean gap: Threads is an active supported
 * platform with a live tool page but had no blog post. Verified live
 * before publishing: a public Threads post with a video returns MP4;
 * text / photo-only posts return nothing, which the guide states.
 */
class PublishThreadsVideoGuide extends Migration
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
                'title' => 'How to Download a Threads Video (Public Posts, Original MP4)',
                'slug' => 'threads-video-downloader-public-post-mp4-guide',
                'excerpt' => 'Threads has no download button, so you copy the public post link and save the original MP4 the way Threads stored it — no re-encode, no watermark. Here is how, why only posts that actually contain a video work, and how to save it on a phone.',
                'content' => <<<'HTML'
<p><strong>Threads does not give you a download button</strong> — Meta would rather you watch inside the app — so saving a video means taking its public link and reading the MP4 that Threads already stored. Done this way you get the original file, not a re-encoded copy, which is why there is no watermark and no quality loss. The one thing to know up front: only a post that actually contains a video returns anything. A text post or a photo post has no video to fetch. This guide shows the steps, that limit, and where the file lands on each phone.</p>
<div class="note"><strong>Public posts, with permission:</strong> Save-Froms only reads a public Threads post link in your browser, it never signs into your account. Save videos you made or have the right to reuse, and never enter your Meta or Instagram password on a download site — a public post never needs it.</div>
<h2>Save a Threads video, step by step</h2>
<ol>
<li>Open the post in the Threads app or on threads.com. Tap the <strong>…</strong> or share icon and choose <strong>Copy link</strong>.</li>
<li>Open the <a href="/threads-downloader">Threads downloader</a> and paste the link. In a few seconds the video appears.</li>
<li>Pick the <strong>MP4</strong> row and the download starts — that is the original file Threads stored.</li>
<li>Open it from your Downloads folder. No watermark, no re-encode, the same quality that was uploaded.</li>
</ol>
<p>A correct link points at the single post and looks like <code>threads.com/@username/post/XXXXXXXX</code>. A link to a profile is not one post, so open the specific post with the video first and copy its own link.</p>
<h2>Why only video posts work</h2>
<p>A Threads post can be text, a photo, or a video — and only the video kind has a file to download. If you paste a text or photo-only post, the tool has nothing to fetch and tells you so. That is not a failure, it is the post simply not containing a video. So when a link does not return anything, first check that the post actually has a playing video in it, then copy that post's own link rather than the profile.</p>
<table><thead><tr><th>Post type</th><th>What you get</th></tr></thead><tbody>
<tr><td>Video post</td><td>The original MP4, no watermark</td></tr>
<tr><td>Photo post</td><td>Nothing to download as video — it is an image post</td></tr>
<tr><td>Text post</td><td>Nothing to fetch; there is no media</td></tr>
</tbody></table>
<h2>No watermark, because nothing is re-encoded</h2>
<p>Tools that re-render a video are the ones that stamp their own logo on it and soften the picture. Saving the original MP4 skips that step entirely: the file you get is the file Threads holds, so it carries no added watermark and loses no quality. If you ever see a branded overlay on a "downloaded" Threads video, it came from a tool that re-encoded it, not from Threads.</p>
<h2>Save the video on your phone</h2>
<p>On <strong>iPhone</strong>, Safari saves the MP4 into the Files app under Downloads; open Files to play it, or tap Share to move it into Photos. On <strong>Android</strong>, Chrome drops it into your Download folder, visible from the Files app or Gallery. If nothing downloads on either phone, you are probably inside the Threads in-app browser — copy the link and open Chrome or Safari separately, then paste it there.</p>
<h2>Threads and Instagram are linked</h2>
<p>Threads runs on the same Meta media backbone as Instagram, so the way a public link is read is similar across both. If you also save from Instagram, the <a href="/blog/instagram-video-downloader-public-links-formats-guide">Instagram video downloader guide</a> covers public links and formats, and the <a href="/blog/dl-instagram-videos-photos-reels-stories-link-cheat-sheet">Instagram link cheat sheet</a> explains what each link type returns. For the general question of which links ever work, see the <a href="/blog/download-video-from-url-link-which-links-work-guide">download a video from a URL guide</a>.</p>
<h2>Threads video not downloading? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>Nothing appears</td><td>The post has no video, or you used a profile link</td><td>Open a post that plays a video and copy its own link</td></tr>
<tr><td>"Could not process, try again"</td><td>A transient provider hiccup</td><td>Paste the link and try once more</td></tr>
<tr><td>Download never starts</td><td>You are in the Threads in-app browser</td><td>Copy the link into Chrome or Safari</td></tr>
<tr><td>The post is private</td><td>A private / locked account</td><td>Only public posts can be read; this is a deliberate limit</td></tr>
<tr><td>You see a watermark</td><td>A re-encoding tool added it</td><td>Use a tool that saves the original file instead</td></tr>
</tbody></table>
<h2>Frequently asked questions</h2>
<h3>Can I download any Threads video?</h3><p>Any public post that contains a video, yes. Private posts are never accessible, and text or photo posts have no video to save.</p>
<h3>Will it have a watermark?</h3><p>No. Saving the original MP4 adds nothing. A watermark only appears if a tool re-encodes the video, which this does not.</p>
<h3>Do I need an app or my password?</h3><p>Neither. A browser saves a public Threads video, and no legitimate tool needs your Meta or Instagram login for a public post.</p>
<h3>Why did my link return nothing?</h3><p>Most often the post has no video, or you copied the profile link instead of the post. Open the single video post and copy its own link.</p>
<p><small>Guide published: November 2, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-threads-video-downloader-public-post-mp4-guide.svg',
                'meta_title' => 'Download a Threads Video: Public Posts, Original MP4, No Watermark',
                'meta_description' => 'How to download a Threads video: copy the public post link and save the original MP4 with no watermark and no re-encode. Only posts that contain a video work. Android and iPhone tips.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['threads-video-downloader-public-post-mp4-guide'])->delete();
    }
}

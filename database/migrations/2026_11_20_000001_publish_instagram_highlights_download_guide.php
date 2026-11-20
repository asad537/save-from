<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "download instagram highlights", "save instagram
 * highlight" and "instagram highlight downloader" (2026-11-20). Distinct
 * from the story guides: Highlights are PERMANENT public saved stories on a
 * profile, so the key difference is copying the Highlight's own link and
 * that it does not expire in 24 hours. Public profiles only. Funnels to
 * /instagram-stories-downloader; links the anonymous-story and story-saver
 * guides to form a tight Stories/Highlights cluster.
 */
class PublishInstagramHighlightsDownloadGuide extends Migration
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
                'title' => 'Download Instagram Highlights: Save a Whole Highlight',
                'slug' => 'instagram-highlights-download-save-whole-highlight-guide',
                'excerpt' => 'Highlights are the stories a profile chose to keep, so unlike a 24-hour story they are still there to save. Here is how to download a public Highlight — each frame of it — using the Highlight\'s own link, with the honest limit on private accounts.',
                'content' => <<<'HTML'
<div class="note"><strong>Save a Highlight now:</strong> open the Highlight on a public profile, copy <strong>its</strong> link, and paste it into the <a href="/instagram-stories-downloader">Instagram Stories downloader</a> — it runs in your browser with <strong>no app and no login</strong>, and returns each frame (photo or video). It works for <strong>public</strong> profiles only.</div>
<p>Highlights are the stories a profile decided to keep permanently, grouped into the little circles under the bio. Because they do not vanish after 24 hours, a Highlight is usually still there to save long after the original story expired — which makes it the reliable way to keep older story content. The one thing people get wrong is which link to copy: you need the <strong>Highlight's own</strong> link, not the profile link. This guide covers that, saving every frame, and the honest limit that only public profiles work.</p>

<h2>Download a Highlight — step by step</h2>
<ol>
<li>Open the public profile and tap the <strong>Highlight circle</strong> so it starts playing.</li>
<li>Open the <strong>…</strong> menu and choose <strong>Copy link</strong> (that copies the Highlight's link). On desktop, open the Highlight and copy the address from the browser bar.</li>
<li>Paste it into the <a href="/instagram-stories-downloader">Instagram Stories downloader</a>.</li>
<li>Save each frame you want — a Highlight is usually several photos and videos.</li>
</ol>
<p>Copy the Highlight's link, not the profile link — a plain <code>instagram.com/username</code> link does not tell the tool which Highlight you mean. For how stories, reels and posts differ as links, see the <a href="/blog/instagram-reels-posts-stories-public-link-guide">reels, posts and stories link guide</a>.</p>

<h2>Highlights vs ordinary stories</h2>
<table><thead><tr><th>Type</th><th>How long it lasts</th><th>Which link to copy</th></tr></thead><tbody>
<tr><td>Story</td><td>24 hours, then gone</td><td>The live story's link (save it before it expires)</td></tr>
<tr><td>Highlight</td><td>Stays until the owner removes it</td><td>The Highlight's own link</td></tr>
</tbody></table>
<p>Because Highlights persist, you are not racing a 24-hour clock. If instead you want a <em>live</em> story without appearing in the viewer list, that is covered in the <a href="/blog/instagram-story-download-anonymously-without-them-knowing-guide">anonymous story download guide</a>, and the <a href="/blog/instagram-story-downloader-story-saver-public-stories-guide">story saver guide</a> explains the 24-hour window.</p>

<h2>Each frame is separate</h2>
<p>A Highlight is a sequence — several photos and videos one after another. The downloader returns the frames so you can pick the ones you want rather than only the first. Save them individually; you do not have to take the whole reel of frames if you only need one.</p>

<h2>Private profiles do not work</h2>
<p>If a profile is private, its Highlights are not public, so there is no link for a downloader to read — and none should exist. Save-Froms reads public links only and never signs into your account, so a private profile simply returns nothing. If you need a Highlight from a private account, follow it and ask the person; there is no legitimate tool that bypasses privacy, and the ones that claim to are after your password.</p>

<h2>Save it on your phone</h2>
<p>On <strong>Android</strong>, Chrome drops each file into your Download folder and it usually shows in Gallery. On <strong>iPhone</strong>, Safari saves to the Files app under Downloads; open a file and tap Share → <strong>Save Image/Video</strong> to move it into Photos. If a download never starts, you are inside Instagram's in-app browser — copy the link into Chrome or Safari. The <a href="/blog/where-do-downloaded-videos-go-iphone-android-find-guide">where downloads go guide</a> helps if you cannot find a saved file.</p>

<h2>Keep it respectful</h2>
<p>A public Highlight is not free to reuse. Keep content you own or have a genuine reason to save, do not repost someone's Highlight as your own, and get permission for anything commercial.</p>

<h2>Highlight will not download? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>Nothing appears</td><td>You copied the profile link</td><td>Open the Highlight and copy its own link</td></tr>
<tr><td>"This account is private"</td><td>Private Highlights are not public</td><td>Follow and ask the owner — no workaround</td></tr>
<tr><td>Only one frame saved</td><td>The Highlight has several frames</td><td>Save each frame you want individually</td></tr>
<tr><td>Download never starts</td><td>You are in Instagram's in-app browser</td><td>Copy the link into Chrome or Safari</td></tr>
</tbody></table>

<h2>Frequently asked questions</h2>
<h3>How do I download an Instagram Highlight?</h3><p>Open the Highlight on a public profile, copy its own link, paste it into the Instagram Stories downloader, and save each frame. In your browser, no app or login.</p>
<h3>Can I download an old Highlight?</h3><p>Yes, as long as it is still on a public profile. Highlights do not expire like 24-hour stories, so old ones are usually still saveable.</p>
<h3>Why do I need the Highlight's link, not the profile link?</h3><p>A profile link does not say which Highlight you mean. Open the Highlight itself and copy its link so the tool knows what to fetch.</p>
<h3>Can I download Highlights from a private account?</h3><p>No. Private Highlights are not public. Follow the account and ask the person instead.</p>
<h3>Does the owner know I saved it?</h3><p>A public-link download does not use your account, so you do not appear in any viewer list.</p>
<p><small>Guide published: 20 November 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-instagram-highlights-download-save-whole-highlight-guide.svg',
                'meta_title' => 'Download Instagram Highlights: Save a Whole Highlight',
                'meta_description' => 'Highlights do not expire like 24-hour stories, so they are still there to save. How to download a public Instagram Highlight — every frame — using its own link.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['instagram-highlights-download-save-whole-highlight-guide'])->delete();
    }
}

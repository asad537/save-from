<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "instagram carousel downloader" and
 * "download all photos / slides from an instagram post" (2026-11-01).
 * Honest angle unique to our backend: a carousel is one post holding
 * many slides, and a single public-link parse already returns every
 * slide, so no bulk / profile scraping is involved.
 */
class PublishInstagramCarouselGuide extends Migration
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
                'title' => 'Instagram Carousel Downloader: Save Every Slide From One Link',
                'slug' => 'instagram-carousel-downloader-save-all-slides-one-link-guide',
                'excerpt' => 'A carousel is a single Instagram post that holds up to 20 photos and videos. Paste the one post link and every slide comes back together — no saving them one by one, no profile scraping, no login. Here is how, and what each slide returns.',
                'content' => <<<'HTML'
<p><strong>An Instagram carousel is one post, not many.</strong> It just holds several photos and videos — up to 20 slides — that you swipe through. That single fact is what makes a carousel easy to save: because it is one public post with one link, pasting that link returns every slide at once. You do not screenshot slide by slide, you do not need a "bulk" tool, and you never touch a profile or a login. This guide shows how to grab all the slides from a public carousel, what each slide comes back as, and how to fix the common "only got the first slide" problem.</p>
<div class="note"><strong>Public posts, with permission:</strong> Save-Froms only reads a public post link in your browser, it never signs into your Instagram account. Save carousels you made or have the right to reuse, and never enter your Instagram password on a download site — a public post never needs it.</div>
<h2>Save a whole carousel, step by step</h2>
<ol>
<li>Open the carousel post in the Instagram app or on instagram.com. Tap the <strong>…</strong> menu (or the share arrow) and choose <strong>Copy link</strong>.</li>
<li>Open the <a href="/instagram-downloader">Instagram downloader</a> and paste the link. In a few seconds every slide in the post is listed.</li>
<li>Download each slide you want, or all of them — photos come as JPG, any video slides come as MP4, each at its original resolution.</li>
<li>Open your Downloads folder and the slides are there as separate files, ready to use.</li>
</ol>
<p>The link should point at the post itself and look like <code>instagram.com/p/XXXXXXXXXX/</code> for a feed carousel. A link to a profile (<code>instagram.com/username</code>) is not one post, so open the carousel first and copy its own link.</p>
<h2>Why one link returns all the slides</h2>
<p>Instagram stores a carousel as a single post object with a list of media inside it. When that one public link is read, the whole list comes back, so a photo-and-video carousel gives you both the JPGs and the MP4s in one pass. This is not the same as a "bulk profile downloader" that tries to scrape every post an account ever made — that needs a login and gets blocked. A carousel is simpler and entirely public: one post, one link, every slide.</p>
<table><thead><tr><th>Carousel type</th><th>What you get</th><th>Format per slide</th></tr></thead><tbody>
<tr><td>All photos</td><td>Every image in the post</td><td>JPG, original resolution</td></tr>
<tr><td>All videos</td><td>Every clip in the post</td><td>MP4, original quality</td></tr>
<tr><td>Mixed photo + video</td><td>Both kinds, in order</td><td>JPG and MP4 together</td></tr>
</tbody></table>
<h2>Only got the first slide? Here is why</h2>
<p>The classic failure is a tool that returns slide one and stops. That happens when it reads only the post's cover image instead of the full media list. A proper carousel save lists every slide, so the quick test is simple: open a post you know has, say, five slides, and count the files you get. Five means it worked, one means the tool is not really reading the carousel. If you only see the first slide, re-paste the post link — not a screenshot URL or a story link, which behave differently.</p>
<h2>Save the slides on your phone</h2>
<p>On <strong>iPhone</strong>, Safari saves each file into the Files app under Downloads; photos can then be moved into Photos and videos play from Files or Photos. On <strong>Android</strong>, Chrome drops them into your Download folder, visible from the Files app or Gallery. If nothing downloads, you are likely inside Instagram's in-app browser — copy the link and open Chrome or Safari separately, then paste it there.</p>
<h2>Carousels, single photos and reels</h2>
<p>If a post is a single photo rather than a carousel, you still paste the same way and get the one image. For moving posts, the <a href="/blog/instagram-reels-download-quality-links-mobile-tips">reels download guide</a> covers quality and links, and the <a href="/blog/dl-instagram-videos-photos-reels-stories-link-cheat-sheet">Instagram link cheat sheet</a> explains what every link type — post, reel, story, profile — actually returns. For the general run of public video posts, see the <a href="/blog/instagram-video-downloader-public-links-formats-guide">Instagram video downloader guide</a>.</p>
<h2>Carousel not downloading? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>Only the first slide saved</td><td>The tool read the cover, not the full post</td><td>Re-paste the post link and count the slides</td></tr>
<tr><td>Nothing appears</td><td>The link was a profile or a story</td><td>Open the single carousel post and copy its link</td></tr>
<tr><td>Download never starts</td><td>You are in Instagram's in-app browser</td><td>Copy the link into Chrome or Safari</td></tr>
<tr><td>403 or "link expired"</td><td>An old prepared link timed out</td><td>Paste the post link again</td></tr>
<tr><td>The post is private</td><td>A locked / private account</td><td>Only public posts can be read; this is a deliberate limit</td></tr>
</tbody></table>
<h2>Frequently asked questions</h2>
<h3>Can I download all photos from an Instagram carousel at once?</h3><p>Yes. A carousel is one post, so pasting its single public link returns every slide together — you do not save them one at a time.</p>
<h3>Is this the same as a bulk profile downloader?</h3><p>No. This reads one public post and its slides. Grabbing every post an account ever made is profile scraping, which needs a login and is a different, blocked thing.</p>
<h3>What format are the slides?</h3><p>Photo slides come as JPG and video slides as MP4, each at the resolution Instagram stored. A mixed carousel gives you both.</p>
<h3>Do I need an app or my password?</h3><p>Neither. A browser saves a public carousel, and no legitimate tool needs your Instagram login for a public post.</p>
<p><small>Guide published: November 1, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-instagram-carousel-downloader-save-all-slides-one-link-guide.svg',
                'meta_title' => 'Instagram Carousel Downloader: Save Every Slide in One Link',
                'meta_description' => 'Download every slide from an Instagram carousel with one public post link: photos as JPG, videos as MP4, no login and no saving slide by slide. Fix the "only first slide" problem.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['instagram-carousel-downloader-save-all-slides-one-link-guide'])->delete();
    }
}

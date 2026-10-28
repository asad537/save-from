<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "instagram photo downloader" (UK 5,400/month, KD 29) plus "download images
 * of instagram" and "download ig pics". Provider test 2026-10-28: a public
 * Instagram photo post returns each image as a full-size JPG with a direct
 * download URL, so the tool page is real, not aspirational.
 */
class AddInstagramPhotoDownloaderPage extends Migration
{
    public function up()
    {
        $now = now();
        DB::table('landing_pages')->updateOrInsert(['slug' => 'instagram-photo-downloader'], [
            'title' => 'Instagram Photo Downloader',
            'eyebrow' => 'Instagram Photos',
            'heading' => '[Instagram Photo] Downloader',
            'intro' => 'Paste a public Instagram post link to save its photos at full size, one JPG per image, including every image in a carousel. No app, no login.',
            'meta_title' => 'Instagram Photo Downloader – Full-Size JPG | Save-Froms',
            'meta_description' => 'Download Instagram photos at full size from a public post link: single posts and carousels, one JPG per image, on Android, iPhone or desktop. No app or login.',
            'content' => $this->content(),
            'faq_items' => json_encode([
                ['question' => 'What size are the downloaded Instagram photos?', 'answer' => 'The largest rendition Instagram serves publicly, usually 1080 pixels on the long edge and up to 1440 for recent uploads. The result row shows the exact dimensions.'],
                ['question' => 'Can I download all photos from a carousel?', 'answer' => 'Yes. Each image in a public carousel is listed as its own row, numbered in order, so you can save one or all of them.'],
                ['question' => 'Can I download a profile picture?', 'answer' => 'No. Profile pictures are not posts and have no post link. Only images inside public posts can be checked.'],
                ['question' => 'Does it work for private accounts?', 'answer' => 'No. The downloader reads only what anyone can see without logging in, and never uses your account.'],
                ['question' => 'Is the photo the original file?', 'answer' => 'No. Instagram re-compresses every upload. The download is Instagram\'s public copy, which is slightly smaller than the creator\'s original.'],
                ['question' => 'Can I use downloaded photos on my own page?', 'answer' => 'Only with the photographer\'s permission. A public post does not grant reuse rights; credit and ask first.'],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'is_active' => 1,
            'sort_order' => 14,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function content(): string
    {
        return <<<'HTML'
<h2>What the Instagram photo downloader returns</h2>
<p>Paste the link of a public Instagram photo post and the result lists every image in it as a separate row: a single photo gives one row, a carousel gives up to twenty, numbered in the order they appear. Each row shows the format (JPG), the pixel size Instagram serves for that image, and a Download button that saves the file directly. Instagram keeps one public rendition per image, so there is no quality menu; the row you see is the largest copy that exists outside the creator's own device.</p>
<h2>Which Instagram links work for photos</h2>
<table><thead><tr><th>Address</th><th>What it is</th><th>Result</th></tr></thead><tbody>
<tr><td><code>instagram.com/p/CxYz…/</code></td><td>A feed post: one photo, a carousel, or a video</td><td>Every photo in the post, or the video if it is a video post</td></tr>
<tr><td><code>instagram.com/p/CxYz…/?img_index=3</code></td><td>A carousel opened on its third slide</td><td>All slides; the index is ignored</td></tr>
<tr><td><code>instagram.com/reel/…</code></td><td>A Reel</td><td>A video, not a photo; use the <a href="/instagram-reels-downloader">Instagram Reels downloader</a></td></tr>
<tr><td><code>instagram.com/stories/username/…</code></td><td>A Story</td><td>The Story's image or video while it is live; see the <a href="/instagram-stories-downloader">Instagram Stories downloader</a></td></tr>
<tr><td><code>instagram.com/username/</code></td><td>A profile</td><td>Nothing; open one post</td></tr>
<tr><td><code>instagram.com/explore/tags/…</code></td><td>A hashtag page</td><td>Nothing; open one post</td></tr>
</tbody></table>
<h2>How to download Instagram photos</h2>
<ol class="sf-steps">
<li><strong>Open the post</strong><span>Tap the photo so it opens on its own, not the profile grid.</span></li>
<li><strong>Copy the link</strong><span>Tap the three dots (or the paper-plane icon) and choose Copy link.</span></li>
<li><strong>Paste it above</strong><span>On a phone, use Chrome or Safari rather than Instagram's in-app browser.</span></li>
<li><strong>Pick the images</strong><span>Each photo in the post is a row; press Download on the ones you want.</span></li>
<li><strong>Find the files</strong><span>Downloads on Android and desktop; Files → Downloads on iPhone, then Share → Save Image to put it in Photos.</span></li>
</ol>
<h2>Sizes and what "full size" means on Instagram</h2>
<p>Instagram stores a few renditions of each photo and serves the largest to the web viewer, typically 1080 pixels on the long edge for older posts and 1440 for uploads made with "Upload at highest quality" switched on. A square photo comes back at 1080 × 1080 or 1440 × 1440; a portrait photo at 1080 × 1350 or 1440 × 1800; a landscape photo at 1080 × 566 or 1440 × 754. Files are 150 to 600 KB each. The creator's original camera file is larger, but Instagram never serves it, so no tool can return it.</p>
<h2>Carousels, mixed posts and collabs</h2>
<p>A carousel is a post with the stacked-squares icon and up to twenty slides, each of which can be a photo or a video. The result lists photos as JPG rows and any videos as MP4 rows, all numbered in slide order, so a mixed post is handled in one go. A collab post appears on two profiles but is one post; either profile's link returns the same images. A photo someone shared to their Story from a post is still the original post; tap through to it and copy its link.</p>
<h2>Why a photo post returns nothing</h2>
<ul>
<li><strong>Private account:</strong> the post is visible only to approved followers inside their own session.</li>
<li><strong>Deleted or archived post:</strong> Instagram shows an error page instead of media.</li>
<li><strong>Age-gated or restricted post:</strong> hidden from signed-out viewers, so there is no public rendition.</li>
<li><strong>Profile or hashtag link:</strong> these list many posts; copy one post's link.</li>
<li><strong>Link opened the Instagram app and nothing copied:</strong> long-press the link and choose Copy, or open instagram.com in a browser and copy the address bar.</li>
</ul>
<h2>Instagram photos on a computer</h2>
<p>On instagram.com in a desktop browser, a post opens on its own page and the address bar shows the <code>/p/</code> link, which you can copy directly; for a carousel, the address gains <code>?img_index=</code> as you click through, and that part can stay. This is the quickest way to save photos from several posts in a row: open each post, copy, paste, download, and the JPGs land in the browser's Downloads folder with the post's caption as the filename.</p>
<h2>Saving your own Instagram photos</h2>
<p>For your own account, Instagram's export under Settings → Your activity → Download your information returns every photo you ever posted, at the same public size, in one archive; it takes up to 48 hours to arrive. This page is faster for one post or a handful, and it is the only route for a public post from another account whose owner has allowed you to keep the images.</p>
<h2>Photos inside Reels, Stories and tagged posts</h2>
<p>The cover frame of a Reel is not a photo post; it is a single frame of the video, and the Reel link returns the MP4 rather than that frame. A photo Story returns its image through the Stories page while the Story is live, at the Story size of 1080 × 1920. A post you are tagged in is an ordinary post on the other account; open it from your Tagged tab and copy its link like any other. Photos in an Instagram Guide or a saved collection are also ordinary posts, each with its own link, and the collection itself has nothing to return.</p>
<h2>Getting the sharpest copy</h2>
<p>Because Instagram serves one rendition, the only way to a sharper image is the creator. Many photographers keep full-resolution versions on a portfolio site linked from their bio, or will send the original on request; for your own posts, the original is still in your phone's camera roll at several times the Instagram size.</p>
<h2>Permission and credit</h2>
<p>A public photo is still the photographer's work. Keep images you made, images whose creator has agreed you may save, or images under a licence that covers your use. If you repost, credit the account by name and link to the original post; Instagram's own "Add to Story" and collab tools are the routes that keep credit automatic.</p>
HTML;
    }

    public function down()
    {
        DB::table('landing_pages')->where('slug', 'instagram-photo-downloader')->delete();
    }
}

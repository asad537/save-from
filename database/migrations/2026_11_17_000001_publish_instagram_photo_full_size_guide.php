<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "instagram photo download", "download instagram
 * photo hd / full size" and the profile-picture / "instagram dp download"
 * cluster (2026-11-17). The Instagram cluster had reels, video, stories and
 * carousel posts but no dedicated full-resolution PHOTO guide in English;
 * the photo tool page (/instagram-photo-downloader) existed with no feeder
 * post. Honest on what "full size" really means (the stored upload, not an
 * upscale) and on profile-picture limits, so it does not over-promise the
 * avatar grab that spam tools claim. Photo backend returns public /p/ image
 * links -> JPG; CTA funnels to the photo tool from the first line.
 */
class PublishInstagramPhotoFullSizeGuide extends Migration
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
                'title' => 'Instagram Photo Download: Save Public Posts in Full Size',
                'slug' => 'instagram-photo-download-full-size-hd-public-posts-guide',
                'excerpt' => 'Save a photo from a public Instagram post at the full resolution it was uploaded in — paste the post link, pick the image, done. Plus the honest truth about "HD", profile-picture (DP) limits, carousels, and private accounts.',
                'content' => <<<'HTML'
<div class="note"><strong>Save a photo now:</strong> copy a public Instagram post link and paste it into the <a href="/instagram-photo-downloader">Instagram photo downloader</a> — it runs in your browser, with <strong>no app and no login</strong>, and returns the picture as a JPG. Below: what "full size" actually means, how to grab every slide of a carousel, and the honest limits on profile pictures.</div>
<p>Downloading an Instagram photo is simple when the post is public and you have its link. You do not need an app, a screenshot, or your Instagram password — the public post link is enough for the <a href="/instagram-photo-downloader">photo downloader</a> to return the image. This guide covers the exact steps on phone and desktop, what resolution you really get, carousels with several photos, profile pictures, and what to do when a photo will not save. Only save photos you own or have permission to use.</p>

<h2>Download an Instagram photo — step by step</h2>
<ol>
<li>Open the post on instagram.com or in the app.</li>
<li>Tap the <strong>…</strong> menu on the post and choose <strong>Copy link</strong>. On a computer, open the post and copy the address from the browser bar.</li>
<li>Paste the link into the <a href="/instagram-photo-downloader">Instagram photo downloader</a>.</li>
<li>Pick the image and save it. If the post has several photos, you will see each one.</li>
</ol>
<p>A correct link points at a single post and usually contains <code>/p/</code> (for example <code>instagram.com/p/XXXXXXXXXXX/</code>). A link to a profile — just <code>instagram.com/username</code> — is not one post, so open the photo first and copy its own link.</p>

<h2>What "full size" and "HD" really mean</h2>
<p>Instagram shrinks photos to fit the feed on screen, but it also stores a larger version of the upload. The downloader fetches that stored file, so you get the picture at the resolution the account actually uploaded — not the small thumbnail you were scrolling past. That is the real meaning of "full size" here. What it is <strong>not</strong>: no tool can add detail that was never uploaded. If someone posted a small or heavily compressed image, the "HD" version is simply that same image — a bigger number next to it does not sharpen it. Treat the uploaded quality as the ceiling. For an all-in-one reference on reels, video, photos and stories, see the <a href="/blog/dl-instagram-videos-photos-reels-stories-link-cheat-sheet">Instagram link cheat sheet</a>.</p>

<h2>A post with several photos (carousel)</h2>
<p>When a post is a carousel — the little dots under it mean more than one photo — one post link covers the whole set, and you can save each slide. The dedicated <a href="/blog/instagram-carousel-downloader-save-all-slides-one-link-guide">carousel guide</a> walks through saving every slide from a single link, including mixed photo-and-video carousels.</p>

<h2>Profile pictures (DP): the honest limits</h2>
<p>Lots of people search for an "Instagram DP download" or a full-size profile picture. Here is the honest version: Instagram shows avatars small and cropped, and there is <strong>no official full-resolution profile-picture download</strong>. The biggest version you can reach is whatever the account uploaded, and for older or low-quality avatars that is not very large. The photo downloader works on <strong>post</strong> links (<code>/p/</code>), not on the round avatar, so it is built for feed photos rather than profile pictures. If someone has posted the same image to their feed, use that post link for a clean copy. Be wary of sites promising a "4K profile picture" — they cannot create resolution that was never there, and the ones demanding a login are a password-phishing risk.</p>

<h2>Private accounts do not work — and that is correct</h2>
<p>If an account is private, its photos are not public, so no link tool can fetch them and none should. Save-Froms only reads public links in your browser and never signs into your account, so a private post simply returns nothing. If you need a private photo, ask the person to send it to you or to make the specific post public. For the full picture of what counts as public, see <a href="/blog/instagram-video-downloader-public-links-formats-guide">the Instagram downloader guide</a>.</p>

<h2>Save the photo on your phone</h2>
<p>On <strong>Android</strong>, Chrome puts the JPG in your Download folder and it usually appears in Gallery or Google Photos. On <strong>iPhone</strong>, Safari saves it into the Files app under Downloads; open the file and tap Share → <strong>Save Image</strong> to send it to Photos. If the save never starts on either phone, you are probably inside Instagram's in-app browser — copy the link and open Chrome or Safari on its own. Not sure where a saved file went? The <a href="/blog/where-do-downloaded-videos-go-iphone-android-find-guide">where downloads go guide</a> covers both phones.</p>

<h2>Copyright and respect</h2>
<p>A public photo is not a free-to-reuse photo. Keep images you own or have permission to use, do not repost someone's picture as your own, and do not crop out credits or watermarks. For anything commercial, get written permission first.</p>

<h2>Photo will not download? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>Nothing appears</td><td>The link was a profile, not a post</td><td>Open the photo and copy its own <code>/p/</code> link</td></tr>
<tr><td>Image looks small</td><td>The upload itself was low resolution</td><td>Nothing to recover; that is the source quality</td></tr>
<tr><td>"This account is private"</td><td>Private posts are not public</td><td>Ask the owner to share or make it public</td></tr>
<tr><td>Save never starts</td><td>You are in Instagram's in-app browser</td><td>Copy the link into Chrome or Safari</td></tr>
<tr><td>Only one photo saved</td><td>It is a carousel with several images</td><td>Save each slide — see the carousel guide</td></tr>
</tbody></table>

<h2>Frequently asked questions</h2>
<h3>How do I download an Instagram photo?</h3><p>Open the post, tap … → Copy link, paste it into the Instagram photo downloader, and save the image. In your browser, with no app and no sign-up.</p>
<h3>Can I download an Instagram profile picture in HD?</h3><p>Only up to the size the account uploaded, and there is no official full-size avatar download. If the same image exists as a feed post, use that post link for a cleaner copy.</p>
<h3>Can I download photos from a private account?</h3><p>No. Private photos are not public, so no link tool can fetch them. Ask the owner to share the photo instead.</p>
<h3>Does it work for carousels with several photos?</h3><p>Yes. One post link covers the whole carousel, and you can save each slide. See the carousel guide for the details.</p>
<h3>What is the maximum quality I can get?</h3><p>The resolution the account uploaded. A tool can hand you that stored file but cannot invent detail that was never posted.</p>
<p><small>Guide published: 17 November 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-instagram-photo-download-full-size-hd-public-posts-guide.svg',
                'meta_title' => 'Instagram Photo Download: Save Public Posts Full Size',
                'meta_description' => 'Save a public Instagram photo at full resolution — paste the post link, pick the image, done. Honest truth about HD, profile-picture (DP) limits, carousels and private accounts.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['instagram-photo-download-full-size-hd-public-posts-guide'])->delete();
    }
}

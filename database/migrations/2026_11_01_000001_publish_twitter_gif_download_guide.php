<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "download gif from twitter / x" and
 * "save twitter gif as mp4" (2026-11-01). The honest angle no thin
 * competitor leads with: an X "GIF" is stored as a silent MP4, so a
 * public link returns MP4, not .gif. Complements the single existing
 * X post (which is about video posts, not GIFs).
 */
class PublishTwitterGifDownloadGuide extends Migration
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
                'title' => 'How to Download a GIF from Twitter / X (and Why It Saves as an MP4)',
                'slug' => 'twitter-x-gif-download-mp4-vs-real-gif-guide',
                'excerpt' => 'Download a GIF from Twitter / X the honest way: copy the post link, and the file you get back is a silent MP4, because that is how X actually stores every "GIF". Here is how to save it, and how to turn it into a real .gif only if you truly need one.',
                'content' => <<<'HTML'
<p><strong>Downloading a GIF from Twitter (now X)</strong> confuses people for one reason nobody warns them about: X does not store GIFs as GIFs. Every animated "GIF" you see in a post is really a short, silent MP4 video that loops. So when you copy a public post link and save it, the file that comes back is an <code>.mp4</code>, not a <code>.gif</code> — and that is correct, not a bug. This guide shows how to save the file from a public post, why it arrives as MP4, and how to convert it to a true GIF only in the rare case you actually need one.</p>
<div class="note"><strong>Public posts, with permission:</strong> Save-Froms only reads a public post link in your browser, it never signs into your X account. Save GIFs you made or have the right to reuse, and never enter your X password on any download site — a public post never needs it.</div>
<h2>Save a Twitter / X GIF, step by step</h2>
<ol>
<li>Open the post with the GIF on x.com or twitter.com. Tap the <strong>share</strong> icon and choose <strong>Copy link to post</strong>, or copy the post URL from your browser's address bar.</li>
<li>Open the <a href="/twitter-downloader">Twitter / X downloader</a> and paste the link. The available file appears in a few seconds.</li>
<li>Pick the MP4 row and the download starts. That is the GIF, saved as a looping video.</li>
<li>Open it from your Downloads folder. It plays on a loop with no sound, exactly like the GIF did in the post.</li>
</ol>
<p>A correct link points at a single post and looks like <code>x.com/username/status/1234567890</code>. A link to a profile, a search, or your timeline is not one post, so open the specific post with the GIF first and copy its own link.</p>
<h2>Why the download is an MP4, not a .gif</h2>
<p>A real <code>.gif</code> file is large, limited to 256 colours, and has no sound. Years ago X (then Twitter) stopped storing animations that way because it was heavy and looked poor. Instead, the moment you upload a GIF it is converted to an MP4 video that autoplays and loops silently. Everything you see labelled "GIF" on X today is that MP4 wearing a GIF badge.</p>
<p>So a downloader can only hand you what X actually stored, which is the MP4. This is a good thing: the MP4 is smaller, sharper, and plays on every phone and app. The word "GIF" on X has become a style of clip, not a file format.</p>
<table><thead><tr><th>What you expect</th><th>What X stores</th><th>What you get</th></tr></thead><tbody>
<tr><td>A .gif file</td><td>A silent looping MP4</td><td>An .mp4 that loops, no sound</td></tr>
<tr><td>Hundreds of KB</td><td>Compressed video</td><td>A small, sharp MP4</td></tr>
<tr><td>Works as a sticker everywhere</td><td>Video, not image</td><td>Convert it first if a GIF is required</td></tr>
</tbody></table>
<h2>When you actually need a real GIF</h2>
<p>For most uses — reposting, saving a reaction, sending in a chat — the MP4 is better and you should keep it as is. You only need a true <code>.gif</code> when something will not accept a video: a few older forums, some email signatures, a wiki, or an app that asks specifically for a GIF image. In that case, save the MP4 first using the steps above, then convert it.</p>
<h3>Turn the MP4 into a .gif</h3>
<p>Use any MP4-to-GIF converter in your browser (search for one and upload the file). Keep the clip short, because a GIF of more than a few seconds balloons in size and still looks worse than the MP4. Expect the <code>.gif</code> to be several times larger than the MP4 you started with and to lose a little colour — that is the format, not the tool.</p>
<h2>Save the GIF on your phone</h2>
<p>On <strong>iPhone</strong>, Safari saves the MP4 into the Files app under Downloads; open Files to play it, or tap Share to send it on. iOS will not drop a video into the Photos "GIFs" album, because it is a video, not an animated image — that is expected. On <strong>Android</strong>, Chrome puts the MP4 in your Download folder, visible from the Files app or your browser's downloads screen. If nothing downloads on either phone, you are probably inside the X app's built-in browser; copy the link and open Chrome or Safari separately.</p>
<h2>Only one file and no quality menu?</h2>
<p>That is normal for a GIF post. Unlike a full video, an X GIF is a single short clip at one resolution, so you will usually see just one MP4 row rather than a list of qualities. If you want the broader picture of saving ordinary video posts from X, see <a href="/blog/save-video-public-x-post-correct-url">how to save a video from a public X post using the correct URL</a>, and for the general question of which links ever work, the <a href="/blog/download-video-from-url-link-which-links-work-guide">download a video from a URL guide</a>.</p>
<h2>GIF not downloading from X? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>Nothing appears</td><td>The link was a profile, search or timeline</td><td>Open the single post and copy its own link</td></tr>
<tr><td>You got an MP4, not a .gif</td><td>X stores every GIF as MP4</td><td>This is correct; convert it only if a real GIF is required</td></tr>
<tr><td>Download never starts</td><td>You are in the X app's in-app browser</td><td>Copy the link into Chrome or Safari</td></tr>
<tr><td>403 or "link expired"</td><td>An old prepared link timed out</td><td>Paste the post link and try again</td></tr>
<tr><td>The post is protected</td><td>A private / locked account</td><td>Only public posts can be read; this is a deliberate limit</td></tr>
</tbody></table>
<h2>Frequently asked questions</h2>
<h3>Why does my Twitter GIF download as an MP4?</h3><p>Because X stores every GIF as a silent looping MP4, not as a <code>.gif</code> file. The downloader returns what X actually holds, so an MP4 is the right, expected result.</p>
<h3>Can I get a real .gif straight from the link?</h3><p>No. The source is always an MP4. Save the MP4, then run it through an MP4-to-GIF converter if you genuinely need the <code>.gif</code> format.</p>
<h3>Does the GIF have sound?</h3><p>No. X GIFs are silent by design, so the MP4 you download has no audio track. Nothing is missing.</p>
<h3>Do I need an app or my X password?</h3><p>Neither. A normal browser saves a public GIF, and no legitimate tool needs your X login for a public post. A site that asks you to sign in is a red flag.</p>
<p><small>Guide published: November 1, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-twitter-x-gif-download-mp4-vs-real-gif-guide.svg',
                'meta_title' => 'Download GIF from Twitter / X: Why It Saves as an MP4',
                'meta_description' => 'How to download a GIF from Twitter / X: copy the public post link and you get a silent looping MP4, because that is how X stores every GIF. Save it, and convert to a real .gif only if needed.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['twitter-x-gif-download-mp4-vs-real-gif-guide'])->delete();
    }
}

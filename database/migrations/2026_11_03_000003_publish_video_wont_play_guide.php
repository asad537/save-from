<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "downloaded video won't play on phone"
 * (2026-11-03). Fast-rank pick: SERP is forums plus repair-tool
 * vendors, intent maps to our audience, and it pairs with the
 * no-sound guide. Honest root cause: unsupported codec, use VLC.
 */
class PublishVideoWontPlayGuide extends Migration
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
                'title' => "Downloaded Video Won't Play on Your Phone? Causes and Quick Fixes",
                'slug' => 'downloaded-video-wont-play-phone-fix-guide',
                'excerpt' => 'A downloaded video that will not play is usually a codec your default player cannot read — not a broken file. The one fix that solves most cases is opening it in VLC. Here is how to tell which cause you have and fix it fast.',
                'content' => <<<'HTML'
<p><strong>A downloaded video that refuses to play</strong> is frustrating, but in most cases the file is fine — your phone's built-in player just cannot read the format inside it. Downloaded videos sometimes use codecs (like VP9, HEVC or Opus) that the stock Photos or default player does not support, so it shows a black screen, an error, or nothing at all. The single fix that solves the majority of these is opening the file in a player that handles everything. This guide covers that, plus the other causes — an incomplete download, a wrong file, or an old device — quickest first.</p>
<div class="note"><strong>Try this first:</strong> install <strong>VLC</strong> (free, iPhone and Android) and open the video with it. VLC plays almost every codec, so if the file is intact, it will usually play straight away.</div>
<h2>The main fix: open it in VLC</h2>
<p>Your phone's default player supports a limited set of formats. A downloaded video can use a codec outside that set, and then the default player fails even though the file is perfectly good. VLC (or MX Player on Android) bundles its own codecs and plays almost anything.</p>
<ol>
<li>Install <strong>VLC</strong> from the App Store or Play Store.</li>
<li>Open VLC, go to its file browser, and find your video in Downloads (or your Files app).</li>
<li>Tap to play. If it plays here but not in the default app, the cause was a codec — nothing was wrong with the file.</li>
</ol>
<p>This one step resolves most "won't play" cases, because it is far more often a player limitation than a damaged video.</p>
<h2>Which cause do you have?</h2>
<table><thead><tr><th>What you see</th><th>Likely cause</th><th>Fix</th></tr></thead><tbody>
<tr><td>Plays in VLC but not the default app</td><td>Unsupported codec</td><td>Keep using VLC, or convert to standard MP4 (H.264)</td></tr>
<tr><td>Won't play in any app, stutters or ends early</td><td>Incomplete / corrupted download</td><td>Download it again, fully</td></tr>
<tr><td>Black screen but you hear sound</td><td>Video codec unsupported</td><td>Open in VLC, or re-download as MP4</td></tr>
<tr><td>"Unsupported file" straight away</td><td>Not really a video / wrong extension</td><td>Check it finished downloading; re-save</td></tr>
</tbody></table>
<h2>Cause 2: the download was incomplete</h2>
<p>If the file will not open in any player and also stutters, freezes, or ends early, it probably did not finish downloading — the connection dropped partway and the file is truncated. The fix is simply to download it again on a stable connection. If a very large file keeps failing near the end, pick a slightly lower resolution so the download completes.</p>
<h2>Cause 3: it is not actually a playable video</h2>
<p>Occasionally what you "downloaded" is a web page saved as a file, or a file with the wrong extension, not a real video. Signs: a tiny file size (a few KB), or an "unsupported file" error instantly. Re-download from the correct link and make sure the save finishes with a sensible size — a real video is usually several megabytes or more. For which links actually give you a playable file, see <a href="/blog/download-video-from-url-link-which-links-work-guide">download a video from a URL</a>.</p>
<h2>Prevent it: download a standard MP4</h2>
<p>The most compatible format is MP4 with H.264 video — it plays on every phone without extra apps. When you save, prefer a standard MP4 row rather than WEBM or the very highest 4K option, which are more likely to use codecs older players struggle with. If your download plays but has no audio instead, that is a separate issue covered in the <a href="/blog/downloaded-video-no-sound-silent-mp4-fix-guide">no-sound fix guide</a>, and the <a href="/blog/mp4-downloader-save-any-public-video-as-mp4-guide">MP4 downloader guide</a> explains choosing MP4 over WEBM.</p>
<h2>Frequently asked questions</h2>
<h3>Why won't my downloaded video play?</h3><p>Most often the file uses a codec your default player cannot read. Open it in VLC, which plays almost every format. If it still won't play anywhere, the download was probably incomplete — try again.</p>
<h3>It plays on my computer but not my phone — why?</h3><p>Your computer has broader codec support. On the phone, use VLC or MX Player, or re-download the video as a standard MP4 (H.264), which plays everywhere.</p>
<h3>Black screen but I can hear sound — what is that?</h3><p>The audio codec is supported but the video codec is not. Open the file in VLC, or re-save it as an MP4.</p>
<h3>How do I avoid this next time?</h3><p>Choose a standard MP4 row when you download, rather than WEBM or the top 4K option, which more often use codecs older players cannot handle.</p>
<p><small>Guide published: November 3, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-downloaded-video-wont-play-phone-fix-guide.svg',
                'meta_title' => "Downloaded Video Won't Play on Phone? Causes and Fixes",
                'meta_description' => "A downloaded video that won't play is usually an unsupported codec, not a broken file. Open it in VLC, re-download if incomplete, or save as standard MP4. iPhone and Android fixes.",
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['downloaded-video-wont-play-phone-fix-guide'])->delete();
    }
}

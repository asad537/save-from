<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "can't save downloaded video to camera roll /
 * gallery" (2026-11-05). Fast-rank pick: the SERP is almost entirely
 * forums (Apple Discussions, Dropbox, imore), the intent is exactly our
 * audience (they downloaded a clip and cannot get it into Photos), and
 * the honest root cause ties to our existing advice: browser downloads
 * land in Files first, and "Save to Photos" fails when the codec is not
 * one Photos accepts -> download a standard MP4 instead.
 */
class PublishSaveToCameraRollGuide extends Migration
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
                'title' => "Can't Save a Downloaded Video to Your Camera Roll? iPhone and Android Fixes",
                'slug' => 'save-downloaded-video-to-camera-roll-gallery-fix-guide',
                'excerpt' => 'A downloaded video that will not save to your Camera Roll or Gallery is usually one of two things: it is sitting in the Files app waiting to be moved, or its format is one Photos will not accept. Here is how to get it into your camera roll on iPhone and Android, quickest first.',
                'content' => <<<'HTML'
<p><strong>Downloaded a video but it will not save to your Camera Roll or Gallery?</strong> This trips up almost everyone, and it is rarely a broken file. There are two usual reasons: on a phone, a video saved from your browser lands in the <strong>Files</strong> app (iPhone) or the <strong>Download folder</strong> (Android) first — not straight into Photos — so you just need to move it. Or, if "Save to Photos" fails or does nothing, the video's <strong>format is one Photos will not accept</strong>, and the fix is to download a standard MP4 instead. This guide walks both, quickest first, for iPhone and Android.</p>
<div class="note"><strong>First thing to know:</strong> a browser download does not go to your Camera Roll automatically. It goes to Files (iPhone) or the Download folder (Android). You then "Save to Photos" to put it in your camera roll.</div>
<h2>iPhone: move it from Files into Photos</h2>
<ol>
<li>Open the <strong>Files</strong> app and go to the <strong>Downloads</strong> folder (under On My iPhone or iCloud Drive).</li>
<li><strong>Press and hold</strong> the video, then tap <strong>Save to Photos</strong> (or Share → Save Video).</li>
<li>It now appears in your Camera Roll in the Photos app.</li>
</ol>
<p>If <strong>Save to Photos is greyed out, missing, or does nothing</strong>, that is the format problem below — not a bug in your phone.</p>
<h2>The real fix when "Save to Photos" fails: download a standard MP4</h2>
<p>Apple's Photos app only accepts certain video formats. If you downloaded a <strong>WEBM</strong> file or an MP4 using a codec Photos does not support, the save silently fails — the file is fine, Photos just refuses it. The fix is simple: download the clip again and pick a <strong>standard MP4</strong> row (H.264) instead of WEBM or the very highest 4K option.</p>
<ol>
<li>Go back to the <a href="/blog/mp4-downloader-save-any-public-video-as-mp4-guide">MP4 download</a> and choose an <strong>MP4</strong> row up to 1080p, not WEBM.</li>
<li>Save that version, then do Files → <strong>Save to Photos</strong> again — a standard MP4 is accepted.</li>
</ol>
<p>This is the single most common reason a download will not reach the Camera Roll, and it is why the <a href="/blog/mp4-downloader-save-any-public-video-as-mp4-guide">MP4 over WEBM</a> choice matters. The same format issue is behind videos that <a href="/blog/downloaded-video-wont-play-phone-fix-guide">will not play</a>.</p>
<h2>Android: find it in the Download folder (and Gallery)</h2>
<ol>
<li>Open <strong>Files</strong> (or <strong>My Files</strong> on Samsung) and open the <strong>Download</strong> folder — browser saves go here.</li>
<li>Many downloads also appear automatically in your <strong>Gallery</strong> under a Download album.</li>
<li>If it is in Downloads but not the Gallery, open it once from the Files app, or move it into the <strong>Movies</strong> or <strong>DCIM</strong> folder so the Gallery picks it up.</li>
</ol>
<p>Android is usually more forgiving about formats than iPhone, but a standard MP4 still shows up most reliably in the Gallery.</p>
<h2>Quick: match your problem to the fix</h2>
<table><thead><tr><th>What happens</th><th>Cause</th><th>Fix</th></tr></thead><tbody>
<tr><td>Video is in Files but not Camera Roll</td><td>Browser saved it to Files, not Photos</td><td>Files → press and hold → Save to Photos</td></tr>
<tr><td>"Save to Photos" greyed out or does nothing</td><td>Format Photos will not accept (WEBM / codec)</td><td>Re-download a standard MP4 (H.264)</td></tr>
<tr><td>"Cannot save to this device" error</td><td>Unsupported format or very large file</td><td>Pick a standard MP4, or a lower resolution</td></tr>
<tr><td>Saved but not in Gallery (Android)</td><td>Gallery has not scanned it yet</td><td>Open it once, or move to Movies/DCIM</td></tr>
<tr><td>Nothing saves at all</td><td>Storage full, or app lacks Photos permission</td><td>Free up space; allow Photos access in settings</td></tr>
</tbody></table>
<h2>Still not there?</h2>
<p>If you cannot even find the downloaded file, it may not have finished — or it is in a folder you have not checked. The <a href="/blog/where-do-downloaded-videos-go-iphone-android-find-guide">where downloaded videos go guide</a> shows the exact folder on each phone and how to find a file by name. And for choosing links that give a real, saveable file in the first place, see <a href="/blog/download-video-from-url-link-which-links-work-guide">which links actually work</a>.</p>
<h2>Frequently asked questions</h2>
<h3>Why won't my downloaded video save to my Camera Roll?</h3><p>Either it is sitting in the Files app and needs Save to Photos, or its format (often WEBM or an unsupported codec) is one Photos will not accept. Re-download a standard MP4 and the save works.</p>
<h3>Why is "Save to Photos" greyed out?</h3><p>Because the video format is not one Photos supports. Download a standard MP4 (H.264) up to 1080p instead of WEBM or 4K, then save it to Photos.</p>
<h3>Where does a browser download go on iPhone?</h3><p>To the Files app, under Downloads — not the Camera Roll. You move it into Photos yourself with Save to Photos.</p>
<h3>My video saved but is not in my Android Gallery — why?</h3><p>The Gallery may not have scanned it yet. Open the file once from the Files app, or move it into the Movies or DCIM folder so the Gallery picks it up.</p>
<h3>It says "cannot save to this device" — what now?</h3><p>That is usually an unsupported format or a very large file. Re-download a standard MP4, or pick a lower resolution, then try saving again.</p>
<p><small>Guide published: November 5, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-save-downloaded-video-to-camera-roll-gallery-fix-guide.svg',
                'meta_title' => "Can't Save a Downloaded Video to Camera Roll? iPhone and Android Fixes",
                'meta_description' => "A downloaded video won't save to your Camera Roll because it is in Files, or its format is one Photos won't accept. Move it with Save to Photos, or re-download a standard MP4. iPhone and Android.",
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['save-downloaded-video-to-camera-roll-gallery-fix-guide'])->delete();
    }
}

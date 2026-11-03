<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "where do downloaded videos go" on iPhone /
 * Android (2026-11-03). Fast-rank pick: the SERP is mostly forums and
 * thin blogs, the intent is a beginner question our own users have
 * right after downloading, and it is evergreen.
 */
class PublishWhereDownloadsGoGuide extends Migration
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
                'title' => 'Where Do Downloaded Videos Go? Find Them on iPhone and Android',
                'slug' => 'where-do-downloaded-videos-go-iphone-android-find-guide',
                'excerpt' => 'Saved a video and cannot find it? On a phone, a browser download almost always lands in one place: the Files app (iPhone) or the Download folder (Android). Here is exactly where to look, and how to find a file by name when it seems to have vanished.',
                'content' => <<<'HTML'
<p><strong>You downloaded a video and now it is nowhere to be seen.</strong> This is the single most common thing people hit right after saving a clip, and the good news is the file is almost never lost — it is just in a folder you have not opened yet. Where it lands depends on <em>how</em> you saved it: a video saved from a browser goes to your phone's downloads area, while a video saved inside an app usually stays locked in that app. This guide shows the exact place to look on iPhone and Android, and how to find a file by name when it seems to have disappeared.</p>
<div class="note"><strong>Short answer:</strong> If you downloaded in your browser (Safari or Chrome), look in the <strong>Files app → Downloads</strong> on iPhone, or the <strong>Download folder</strong> (Files / My Files) on Android. That is where a browser-based save lands.</div>
<h2>On iPhone: where your download lands</h2>
<p>A video saved from Safari or Chrome goes to the Files app, not the Photos app, unless you moved it.</p>
<ol>
<li>Open the <strong>Files</strong> app (the blue folder icon).</li>
<li>Tap <strong>Browse</strong>, then <strong>On My iPhone</strong> (or <strong>iCloud Drive</strong>), and open the <strong>Downloads</strong> folder.</li>
<li>Your video is there. Tap to play it, or press and hold to <strong>Save to Photos</strong> if you want it in your camera roll.</li>
</ol>
<p>Quicker route: in Safari, tap the <strong>download icon</strong> (the arrow in the address bar) to see your recent downloads and jump straight to the file.</p>
<h2>On Android: where your download lands</h2>
<ol>
<li>Open <strong>Files</strong> (or <strong>My Files</strong> on Samsung), the built-in file manager.</li>
<li>Open the <strong>Download</strong> folder — browser saves go here by default.</li>
<li>Videos may also appear in your <strong>Gallery</strong> under a Download album. On Samsung, the path is often <em>My Files → Internal storage → Download</em>.</li>
</ol>
<p>Quicker route: open Chrome, tap the <strong>⋮</strong> menu, and choose <strong>Downloads</strong> to see everything you have saved and tap straight through to it.</p>
<h2>iPhone vs Android: quick reference</h2>
<table><thead><tr><th>You saved from…</th><th>iPhone</th><th>Android</th></tr></thead><tbody>
<tr><td>Safari / Chrome download</td><td>Files app → Downloads</td><td>Files → Download folder</td></tr>
<tr><td>Browser's own list</td><td>Safari download icon</td><td>Chrome menu → Downloads</td></tr>
<tr><td>After "Save to Photos / Gallery"</td><td>Photos app</td><td>Gallery → Download album</td></tr>
<tr><td>Saved inside an app</td><td>Stays in that app</td><td>Stays in that app</td></tr>
</tbody></table>
<h2>Still cannot find it? Find it by name</h2>
<p>If the folders look empty, the file is usually still there under a name you did not notice. Two reliable ways to surface it:</p>
<ul>
<li><strong>Check the browser's download list first</strong> — it links straight to the file wherever it saved, which is the fastest way to locate a "missing" download.</li>
<li><strong>Search by file name</strong> in the Files / My Files app. Downloads often keep the video's title or a string of numbers; type part of it into the search bar and the file appears with its real location.</li>
</ul>
<p>If you never saw a download finish at all, it may not have completed — paste the link again and re-save. The <a href="/blog/savefrom-video-downloader-mobile-android-iphone">Android and iPhone download guide</a> covers the full saving flow, and if a download keeps failing, the <a href="/blog/youtube-downloader-not-working-fix">downloader not working guide</a> explains link and "preparing" errors.</p>
<h2>Why some app downloads never appear in Files</h2>
<p>If you tapped "download" <em>inside</em> an app (a streaming app, for example), that video usually stays locked inside the app and will not show up in Files or your Gallery — often for licensing reasons. Those are not real files you can move. A video you save from a browser, on the other hand, is a normal file that lives in Downloads and you can play, move or share freely. For the general question of which links give you a real, movable file, see <a href="/blog/download-video-from-url-link-which-links-work-guide">download a video from a URL</a>.</p>
<h2>Frequently asked questions</h2>
<h3>Where do browser video downloads go on iPhone?</h3><p>To the Files app, under On My iPhone (or iCloud Drive) → Downloads. They do not go to the Photos app unless you choose "Save to Photos".</p>
<h3>Where is the Download folder on Android?</h3><p>Open the Files or My Files app and look in the Download folder, usually under Internal storage. Videos may also show in your Gallery's Download album.</p>
<h3>I downloaded a video but cannot find it anywhere.</h3><p>Open your browser's own Downloads list — it links straight to the file. Or search the file name in the Files app. If nothing shows, the download may not have finished; try again.</p>
<h3>Why can't I find a video I saved inside an app?</h3><p>App downloads usually stay inside that app and are not real files you can open elsewhere. Only browser downloads appear in Files or your Gallery.</p>
<p><small>Guide published: November 3, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-where-do-downloaded-videos-go-iphone-android-find-guide.svg',
                'meta_title' => 'Where Do Downloaded Videos Go? iPhone and Android',
                'meta_description' => 'Cannot find a downloaded video? On iPhone look in Files app → Downloads; on Android the Download folder or Gallery. Plus how to find a saved video by name when it seems missing.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['where-do-downloaded-videos-go-iphone-android-find-guide'])->delete();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * English guide targeting "download youtube videos on laptop / pc /
 * computer" (2026-10-30): a device how-to with no English counterpart
 * (only a mobile guide and a general link guide existed).
 */
class PublishYoutubeOnPcGuide extends Migration
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
                'title' => 'How to Download YouTube Videos on a Laptop or PC (Any Browser, No Software)',
                'slug' => 'download-youtube-videos-on-laptop-pc-public-link-guide',
                'excerpt' => 'Download YouTube videos on a Windows laptop or Mac without installing anything: copy the public link, paste it in your browser, pick an MP4 quality, and open the file from your Downloads folder. 1080p and 4K tips included.',
                'content' => <<<'HTML'
<p><strong>Downloading a YouTube video on a laptop</strong> is easier than on a phone, because a computer saves files in a plain Downloads folder you can see. You do not need a program, an extension, or an account. Copy the video's public link, paste it into a tool in your normal browser, choose an MP4 quality, and the file lands in Downloads. This guide shows the exact steps on Windows and Mac, which quality to pick on a big screen, where the file goes, and how to fix a download that will not start.</p>
<div class="note"><strong>Public videos, with permission:</strong> Save-Froms only reads a public video link in your browser, it never signs into your YouTube account. Keep videos you own or have the creator's permission to save, and do not reupload someone else's work.</div>
<h2>Download a YouTube video on your laptop, step by step</h2>
<ol>
<li>Open the video on youtube.com in Chrome, Edge, Safari or Firefox. Copy the link from the address bar, or use <strong>Share → Copy link</strong>.</li>
<li>Open the <a href="/youtube-downloader">YouTube downloader</a> in another tab and paste the link. The available formats appear in a few seconds.</li>
<li>Pick a row, for example <strong>MP4 1080p</strong>, and the download starts.</li>
<li>When it finishes, open your <strong>Downloads</strong> folder and double-click the file. That is it, no install step anywhere.</li>
</ol>
<p>A link that points at a channel, a playlist, or search results is not one video, so open the single video first and copy its own <code>youtube.com/watch?v=</code> or <code>youtu.be/</code> link.</p>
<h2>Which quality to pick on a big screen</h2>
<p>On a laptop or an external monitor, lower resolutions look soft, so aim higher than you would on a phone. These are rough sizes for a 10 minute video.</p>
<table><thead><tr><th>Quality</th><th>Size (10 min)</th><th>When to choose it</th></tr></thead><tbody>
<tr><td>MP4 720p</td><td>about 100 MB</td><td>Quick save, small file, older laptop</td></tr>
<tr><td>MP4 1080p</td><td>about 200 MB</td><td>Best everyday choice for a laptop screen</td></tr>
<tr><td>MP4 1440p</td><td>about 400 MB</td><td>A sharp external monitor</td></tr>
<tr><td>MP4 2160p (4K)</td><td>1 GB or more</td><td>4K screen or editing, only if the video is 4K</td></tr>
</tbody></table>
<p>A quality row only appears if the creator uploaded at least that high. If 1080p is the top row, the video itself is 1080p and no tool can make a true 4K out of it.</p>
<h2>Where the file goes on Windows and Mac</h2>
<p>On <strong>Windows</strong>, downloads land in <code>C:\Users\YourName\Downloads</code>; your browser also shows them in the downloads bar at the top or the arrow icon near the address bar. On a <strong>Mac</strong>, they go to the Downloads folder in Finder, and the file shows in the Dock's Downloads stack. If you want it somewhere else, drag it out of Downloads once it has finished, or set your browser to "Ask where to save each file" so it prompts you every time.</p>
<h2>No software or extension needed</h2>
<p>Everything here happens in a normal browser tab. Be careful with desktop programs that promise "unlimited 4K downloads", because installers in this category are a common way to pick up bundled adware on a PC. A browser-based save needs no install, asks for no account, and cannot change anything on your computer beyond putting one video file in Downloads. If a site tells you to install an <code>.exe</code> first, close it.</p>
<h2>Want just the audio instead?</h2>
<p>If you only need the sound, grab an MP3 rather than the full video. The <a href="/youtube-mp3-downloader">YouTube to MP3 tool</a> works the same way, and the <a href="/blog/youtube-to-mp3-320kbps-public-link-audio-quality-guide">audio quality guide</a> explains which bitrate you can really expect.</p>
<h2>1080p and 4K: when you actually get them</h2>
<p>High rows take a moment longer because the video and audio are joined into one file before the download starts; if you see "Preparing", wait a few seconds. For the full picture on each, see the <a href="/blog/youtube-to-mp4-1080p-public-link-quality-guide">1080p MP4 guide</a> and the <a href="/blog/youtube-4k-video-download-mp4-2160p-quality-guide">4K (2160p) guide</a>. On a phone instead of a laptop, the steps are slightly different; the <a href="/blog/download-video-yt-mobile-guide">mobile guide</a> covers that.</p>
<h2>Not downloading on your laptop? Quick fixes</h2>
<table><thead><tr><th>Problem</th><th>Why</th><th>Fix</th></tr></thead><tbody>
<tr><td>No formats appear</td><td>The link was a channel, playlist or search page</td><td>Open one video and copy its own link</td></tr>
<tr><td>Download is blocked</td><td>Browser flagged the file</td><td>Click "Keep" in the downloads bar; the MP4 is safe</td></tr>
<tr><td>403 or "link expired"</td><td>A prepared link timed out</td><td>Paste the link and choose the quality again</td></tr>
<tr><td>Only 360p or 720p offered</td><td>The video was uploaded at that quality</td><td>Pick the top row available; it cannot go higher</td></tr>
<tr><td>File will not open</td><td>No media player set</td><td>Open it with VLC or your browser, both play MP4</td></tr>
</tbody></table>
<h2>Frequently asked questions</h2>
<h3>Do I need to install anything on my PC?</h3><p>No. A normal browser tab is enough on Windows and Mac. Avoid downloaders that ask you to install an <code>.exe</code> first.</p>
<h3>Where do my downloads go?</h3><p>The Downloads folder by default, on both Windows and Mac. Your browser's downloads bar links straight to the file.</p>
<h3>Can I download a 4K video on a laptop?</h3><p>Only if the video was uploaded in 4K. If the top row is 1080p, that is the real source and a 4K file is not possible.</p>
<h3>Is a browser download safe for my computer?</h3><p>Yes, it only saves a video file. The risk comes from installed "downloader" programs, not from saving an MP4 in your browser.</p>
<p><small>Guide published: October 30, 2026.</small></p>
HTML,
                'featured_image' => 'images/blog/cover-download-youtube-videos-on-laptop-pc-public-link-guide.svg',
                'meta_title' => 'Download YouTube Videos on Laptop or PC (No Software)',
                'meta_description' => 'How to download YouTube videos on a PC or Mac laptop: paste a public link in any browser, choose MP4 1080p or 4K, and open it from Downloads. No install, no extension.',
            ],
        ];
    }

    public function down()
    {
        DB::table('blog_posts')->whereIn('slug', ['download-youtube-videos-on-laptop-pc-public-link-guide'])->delete();
    }
}

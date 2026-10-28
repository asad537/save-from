<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Keyword of the day (2026-10-28): "mp4 downloader" (UK 5,400/month, KD 27).
 * Cross-platform guide on getting an MP4 from any supported link, and what to
 * do when a platform hands back WEBM instead.
 */
class PublishMp4DownloaderGuide extends Migration
{
    public function up()
    {
        $now = now();
        DB::table('blog_posts')->updateOrInsert(['slug' => 'mp4-downloader-save-any-public-video-as-mp4-guide'], [
            'title' => 'MP4 Downloader: Save Any Public Video as MP4, and What to Do When You Get WEBM',
            'excerpt' => 'How an MP4 downloader works across YouTube, Instagram, TikTok, Facebook and the rest: which platforms return MP4 directly, why YouTube serves WEBM above 1080p, and how to convert without losing quality.',
            'content' => <<<'HTML'
<p>An <strong>MP4 downloader</strong> is what most people are really looking for when they search for a video downloader: a file that plays on a phone, a laptop, a television and inside an editor without any fuss. MP4 is the container that does that. The catch is that platforms do not all store video the same way, so the same tool can hand you an MP4 from one link and a WEBM from another. This guide explains which platforms return MP4 directly, why some rows come back as WEBM, how to turn one into the other without losing quality, and what "MP4" actually guarantees about playback.</p>
<div class="note"><strong>Public links, your own files:</strong> Save-Froms lists the files a public link returns and never uses your account. Keep videos you made, videos whose creator allows downloads, or material you are licensed to use.</div>
<h2>Which platforms give you MP4 straight away</h2>
<table><thead><tr><th>Platform</th><th>What the result lists</th><th>MP4 at every quality?</th></tr></thead><tbody>
<tr><td>YouTube</td><td>MP4 at 144p to 1080p, WEBM at 1440p and 2160p, MP3 audio</td><td>Up to 1080p; higher rows are WEBM</td></tr>
<tr><td>Instagram</td><td>One MP4 (720p or 1080p)</td><td>Yes</td></tr>
<tr><td>TikTok</td><td>One or two MP4 rows, plus MP3</td><td>Yes</td></tr>
<tr><td>Facebook</td><td>SD and HD MP4</td><td>Yes</td></tr>
<tr><td>X / Twitter</td><td>Up to three MP4 bitrates</td><td>Yes</td></tr>
<tr><td>Vimeo</td><td>MP4 at 240p to 4K when the creator allows it</td><td>Yes</td></tr>
<tr><td>Dailymotion</td><td>MP4 at 240p to 1080p</td><td>Yes</td></tr>
<tr><td>Twitch</td><td>MP4 clips and VODs</td><td>Yes</td></tr>
<tr><td>Threads</td><td>One 720p MP4</td><td>Yes</td></tr>
</tbody></table>
<p>So the only place the "MP4 downloader" promise bends is YouTube above 1080p. Every other supported platform returns MP4 at every quality it has, and the <a href="/youtube-to-mp4">YouTube to MP4 page</a> covers the YouTube rows in detail.</p>
<h2>Download a video as MP4 in four steps</h2>
<ol>
<li>Open the video itself and copy its link from the Share menu or the address bar. A profile, playlist or feed link returns nothing.</li>
<li>Paste it into the downloader on the <a href="/">homepage</a>, or the platform's own page, and press Download.</li>
<li>Read the badge on each row. <strong>MP4</strong> rows play everywhere; <strong>WEBM</strong> rows need a modern player; <strong>MP3</strong> rows are audio only.</li>
<li>Choose the MP4 row that fits the screen: 720p for phones, 1080p for laptops and TVs, the largest row for editing.</li>
</ol>
<p>Rows labelled "Preparing" are being assembled from separate video and audio streams. That is normal for 1080p MP4 on YouTube and takes longer on long videos.</p>
<h2>Why a row is WEBM instead of MP4</h2>
<p>MP4 and WEBM are containers, and what matters inside them is the codec. YouTube encodes its highest resolutions in VP9 and AV1, and stores them in WEBM. It does not keep an H.264 copy at 1440p or 2160p, so no downloader can return a 4K MP4 from YouTube; it can only return the WEBM YouTube has, or convert it afterwards. The <a href="/blog/youtube-4k-video-download-mp4-2160p-quality-guide">YouTube 4K guide</a> shows what that looks like on the result page. Everything up to 1080p exists as H.264 MP4, which is why those rows carry the MP4 badge.</p>
<h2>WEBM to MP4 without losing quality</h2>
<p>If a device refuses the WEBM, convert it on your computer. Two routes:</p>
<table><thead><tr><th>Method</th><th>Command or steps</th><th>Quality</th><th>Time</th></tr></thead><tbody>
<tr><td>Remux (no re-encode)</td><td><code>ffmpeg -i video.webm -c copy video.mp4</code></td><td>Identical</td><td>Seconds</td></tr>
<tr><td>Re-encode to H.264</td><td><code>ffmpeg -i video.webm -c:v libx264 -crf 18 -preset slow -c:a aac video.mp4</code></td><td>Visually identical at CRF 18</td><td>Minutes, depends on length</td></tr>
<tr><td>HandBrake (app)</td><td>Open the WEBM, preset "Fast 1080p30" or "Super HQ 2160p60", Start</td><td>Good; choose a high-quality preset</td><td>Minutes</td></tr>
<tr><td>VLC</td><td>Media → Convert / Save → profile "Video – H.264 + MP3 (MP4)"</td><td>Lower; VLC uses conservative settings</td><td>Minutes</td></tr>
</tbody></table>
<p>Try the remux first. It only works when the player you are targeting supports VP9 or AV1 inside MP4, which modern phones, Chrome, Edge, VLC and recent TVs do. Old TVs, car systems and some editors want H.264, which is the re-encode route.</p>
<h2>What "MP4" does and does not guarantee</h2>
<p>MP4 is a box; the video inside can be H.264, H.265 or AV1. H.264 plays on everything made in the last fifteen years. AV1 in MP4, which YouTube sometimes serves for 1080p, plays on recent devices only. If an MP4 row will not play on an older device, the codec is the reason, and the re-encode command above fixes it. Audio inside MP4 is almost always AAC, which plays everywhere.</p>
<h2>MP4 file sizes by platform</h2>
<table><thead><tr><th>Source</th><th>One minute at 720p</th><th>One minute at 1080p</th></tr></thead><tbody>
<tr><td>YouTube</td><td>15 to 30 MB</td><td>30 to 60 MB</td></tr>
<tr><td>Instagram and Threads</td><td>8 to 15 MB</td><td>15 to 30 MB</td></tr>
<tr><td>TikTok</td><td>11 to 19 MB</td><td>19 to 38 MB</td></tr>
<tr><td>Facebook</td><td>10 to 20 MB</td><td>20 to 40 MB</td></tr>
<tr><td>Vimeo</td><td>20 to 35 MB</td><td>40 to 120 MB</td></tr>
</tbody></table>
<p>Vimeo's files are larger because it keeps higher bitrates; social platforms compress harder. The <a href="/blog/video-resolution-vs-file-size-360p-720p-1080p">resolution and file-size guide</a> explains how duration, bitrate and resolution combine.</p>
<h2>MP4 downloader on Android, iPhone and desktop</h2>
<p>The result page is the same everywhere; only the file's destination differs. Android browsers save to Downloads, and the gallery indexes the MP4 within seconds. iPhone's Safari saves to Files → Downloads; open the MP4 there, tap Share and choose Save Video to put it in Photos. Desktop browsers save to the Downloads folder. The in-app browsers inside Instagram, Facebook and TikTok usually block downloads, so copy the link and switch to Chrome or Safari first. The <a href="/blog/savefrom-video-downloader-mobile-android-iphone">Android and iPhone guide</a> has screenshots of each step.</p>
<h2>When no MP4 appears</h2>
<ul>
<li><strong>The link is a collection:</strong> profile, playlist, hashtag or feed. Open one video.</li>
<li><strong>The post is not public:</strong> private account, friends-only, members-only or deleted. Only public media can be read.</li>
<li><strong>Only WEBM rows show:</strong> you are looking at YouTube above 1080p; scroll to the 1080p row or convert.</li>
<li><strong>Only an audio row shows:</strong> the link is a TikTok photo-mode post or a Threads text post with no video.</li>
<li><strong>The row says "temporarily unavailable":</strong> the provider could not fetch that rendition; pick another row or retry in a few minutes. The <a href="/download-troubleshooting">troubleshooting page</a> lists every message.</li>
</ul>
<h2>Frequently asked questions</h2>
<h3>Is an MP4 downloader the same as a video downloader?</h3><p>Nearly. Every supported platform returns MP4 for its standard qualities, so a video downloader is an MP4 downloader in practice; YouTube's 1440p and 4K rows are the one exception.</p>
<h3>Can I download YouTube 4K as MP4?</h3><p>Not directly. YouTube stores 4K as WEBM. Download the WEBM and remux or re-encode it with the commands above.</p>
<h3>Why does the MP4 not play on my TV?</h3><p>The codec inside is probably AV1 or VP9. Re-encode to H.264 with the FFmpeg command and it will play.</p>
<h3>Does converting WEBM to MP4 lose quality?</h3><p>A remux loses nothing. A re-encode at CRF 18 is visually identical for normal viewing.</p>
<h3>Is there a size limit?</h3><p>No limit on this site. Long 1080p videos can exceed 1 GB, so check free space and use Wi-Fi.</p>
<p><small>Guide published: October 28, 2026.</small></p>
HTML,
            'featured_image' => 'images/blog/mp4-downloader-any-platform-guide.svg',
            'meta_title' => 'MP4 Downloader: Save Any Public Video as MP4 | Save-Froms',
            'meta_description' => 'How an MP4 downloader works on YouTube, Instagram, TikTok, Facebook and more: which platforms return MP4 directly, why YouTube serves WEBM above 1080p, and lossless conversion.',
            'language' => 'en',
            'status' => 'published',
            'published_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down()
    {
        DB::table('blog_posts')->where('slug', 'mp4-downloader-save-any-public-video-as-mp4-guide')->delete();
    }
}

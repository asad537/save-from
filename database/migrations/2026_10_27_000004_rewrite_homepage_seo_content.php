<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Homepage targets "video downloader" / "online video downloader":
 * keyword-first title, specific description, and a 900+ word body that
 * links every platform and tool page (the homepage is the strongest URL).
 */
class RewriteHomepageSeoContent extends Migration
{
    public function up()
    {
        DB::table('homepage_seo_contents')->where('is_active', 1)->update([
            'heading' => 'Online Video Downloader for YouTube, Instagram, TikTok, Facebook and More',
            'intro' => 'Save-Froms is a free video downloader that works from one public link. Paste the address of a video, Reel, Short, clip or post, see the MP4, WEBM and MP3 files the source returns, and save the one you are allowed to keep. No app, no extension, no account.',
            'meta_title' => 'Video Downloader Online – Free MP4 & MP3 | Save-Froms',
            'meta_description' => 'Free online video downloader: paste a public YouTube, Instagram, TikTok or Facebook link and save it as MP4 or MP3 in your browser. No app, no account.',
            'content' => $this->content(),
            'updated_at' => now(),
        ]);
    }

    private function content(): string
    {
        return <<<'HTML'
<h2>Which video downloader page should you use?</h2>
<p>Every platform shares links differently, so each one has its own page with the exact steps, link formats and limits. The homepage box accepts any supported link; the platform pages add the detail.</p>
<table>
<thead><tr><th>Platform</th><th>Links that work</th><th>What you get</th><th>Dedicated tools</th></tr></thead>
<tbody>
<tr><td><a href="/youtube-downloader">YouTube video downloader</a></td><td>watch, youtu.be and Shorts links</td><td>MP4 and WEBM up to the uploaded resolution, plus MP3 audio</td><td><a href="/youtube-to-mp4">YouTube to MP4</a>, <a href="/youtube-mp3-downloader">YouTube to MP3</a>, <a href="/youtube-shorts-downloader">YouTube Shorts</a></td></tr>
<tr><td><a href="/instagram-downloader">Instagram video downloader</a></td><td>public Reel and video post links</td><td>MP4 of the public rendition</td><td><a href="/instagram-reels-downloader">Instagram Reels</a>, <a href="/instagram-stories-downloader">Instagram Stories</a></td></tr>
<tr><td><a href="/tiktok-downloader">TikTok video downloader</a></td><td>tiktok.com video links and vm/vt short links</td><td>MP4, often without the watermark, plus the sound</td><td><a href="/tiktok-mp4-downloader">TikTok MP4</a>, <a href="/tiktok-mp3-downloader">TikTok MP3</a></td></tr>
<tr><td><a href="/facebook-downloader">Facebook video downloader</a></td><td>video, Watch, Reel and fb.watch links</td><td>SD and HD MP4 when Facebook returns both</td><td><a href="/facebook-reels-downloader">Facebook Reels</a></td></tr>
<tr><td><a href="/twitter-downloader">Twitter / X video downloader</a></td><td>x.com and twitter.com status links</td><td>MP4 at several bitrates</td><td>—</td></tr>
<tr><td><a href="/vimeo-downloader">Vimeo video downloader</a></td><td>public vimeo.com video pages</td><td>MP4 renditions the creator allows</td><td>—</td></tr>
<tr><td><a href="/dailymotion-downloader">Dailymotion video downloader</a></td><td>dailymotion.com/video and dai.ly links</td><td>MP4 from SD to 1080p</td><td>—</td></tr>
<tr><td><a href="/twitch-downloader">Twitch clip downloader</a></td><td>clips.twitch.tv and public VOD links</td><td>MP4 clips and VODs before they expire</td><td>—</td></tr>
</tbody>
</table>
<p>The full list, with what each platform can and cannot return, is on the <a href="/supported-sites">supported sites</a> page.</p>

<h2>How to download a video from a link</h2>
<ol>
<li><strong>Open the video itself.</strong> A profile, channel, playlist, feed or search page points to many items; the downloader needs the address of one video, Reel, Short, clip or post.</li>
<li><strong>Copy the link.</strong> On a phone, use the platform's Share menu and choose Copy link. On a computer, copy it from the address bar.</li>
<li><strong>Paste it into the box at the top of this page</strong> and press Download. Save-Froms checks that the link is public and asks the provider which files exist for it.</li>
<li><strong>Compare the rows.</strong> Each row shows the format, the resolution or bitrate and the estimated size, so you can pick a file that suits your screen and storage.</li>
<li><strong>Save the file.</strong> The browser puts it in Downloads on Android and desktop, or in the Files app on iPhone.</li>
</ol>
<p>A result usually appears within a few seconds. Higher resolutions such as 1080p and 4K are stored by some platforms as video-only streams, so those rows take a little longer while the audio is joined to the picture.</p>

<h2>MP4, WEBM or MP3: which file to choose</h2>
<p><strong>MP4</strong> plays everywhere and is the right choice for phones, laptops, televisions and most editors. <strong>WEBM</strong> appears for YouTube's highest qualities; it plays in Chrome, Firefox, Edge and VLC, but some older players skip it. <strong>MP3</strong> and other audio rows contain the sound only, which suits a lecture, a podcast or a track you have permission to keep.</p>
<p>Resolution is not the only thing that sets file size. Duration, frame rate and bitrate matter as much, so a ten-minute 1080p file can be anywhere from 150 MB to 600 MB. The <a href="/blog/video-resolution-vs-file-size-360p-720p-1080p">resolution and file-size guide</a> shows what to expect for 360p, 720p and 1080p, and the <a href="/blog/savefrom-mp4-vs-mp3-quality-size-guide">MP4 vs MP3 guide</a> explains when audio-only is the better download.</p>

<h2>Works on Android, iPhone and desktop without an app</h2>
<p>The whole process runs inside Chrome, Safari, Firefox or Edge, so there is nothing to install and nothing that asks for permissions on your device. On a phone, leave the social app first and paste the link into your browser; the in-app browsers inside Facebook, Instagram and TikTok often block downloads. The <a href="/blog/savefrom-video-downloader-mobile-android-iphone">Android and iPhone guide</a> walks through where each browser saves the file.</p>

<h2>Why a link sometimes returns no file</h2>
<table>
<thead><tr><th>What you see</th><th>Usual reason</th><th>What to do</th></tr></thead>
<tbody>
<tr><td>"Not supported" or no result</td><td>The address is a profile, playlist or feed rather than one video</td><td>Open the video and copy its own link</td></tr>
<tr><td>No formats listed</td><td>The video is private, deleted, members-only, age-restricted or region-locked</td><td>Only public media can be checked; ask the owner for a public link</td></tr>
<tr><td>Download starts, then fails or shows 403</td><td>The temporary file address expired</td><td>Paste the original link again and choose the row again</td></tr>
<tr><td>Only low qualities appear</td><td>The source was uploaded at that quality</td><td>No downloader can add resolution that the upload never had</td></tr>
</tbody>
</table>
<p>More cases, with step-by-step fixes, are in the <a href="/download-troubleshooting">download troubleshooting guide</a> and the <a href="/faq">FAQ</a>.</p>

<h2>Public links and permission</h2>
<p>Save-Froms reads only links that are already public, using no account of yours. It cannot and does not bypass privacy settings, logins or paywalls. Being able to download a file does not give you the right to reuse it: keep videos you made, videos whose creator has allowed downloads, and material you are licensed to use. Our <a href="/about">About</a> page explains how the service is run and the <a href="/terms-of-service">Terms of Service</a> set out acceptable use.</p>

<h2>Frequently asked questions</h2>
<h3>Is this video downloader free?</h3>
<p>Yes. There is no account, no subscription and no download limit for ordinary use.</p>
<h3>Do I need to install an app or extension?</h3>
<p>No. Everything happens in your browser. A site that asks you to install an APK, an .exe or a "helper" extension to download a video is adding risk, not features.</p>
<h3>Can I download private videos?</h3>
<p>No. A public-link tool can only see what anyone could see without logging in.</p>
<h3>What is the highest quality I can download?</h3>
<p>Whatever the platform returns for that video: up to 4K on YouTube when the upload is 4K, HD on Facebook when it exists, and the single public rendition on Instagram and TikTok.</p>
<h3>Where does the file go?</h3>
<p>Into your browser's Downloads folder on Android and desktop, and into the Files app on iPhone, from where you can save a video to Photos.</p>
HTML;
    }

    public function down()
    {
        // Homepage copy changes are forward-only.
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateYoutubeLandingContent extends Migration
{
    public function up()
    {
        $content = <<<'HTML'
<h2>What can Save-Froms download from a YouTube link?</h2>
<p>Save-Froms is a browser-based YouTube downloader that checks one complete public video link and displays the download choices returned for that source. Depending on the video, the results may include MP4 or WEBM video and MP3 audio options. Each result shows its format, quality, and estimated file size so you can compare the available choices before downloading.</p>
<p>Select the resolution or audio quality you need, press <strong>Download</strong>, and save the file without creating an account or installing a separate application.</p>

<div class="sf-facts">
  <div><strong>Input</strong><span>One complete public YouTube URL</span></div>
  <div><strong>Output</strong><span>Available MP4/WEBM video or MP3 audio</span></div>
  <div><strong>Devices</strong><span>Android, iPhone, tablets, Windows, and macOS</span></div>
  <div><strong>Account or app</strong><span>Not required for the browser workflow</span></div>
</div>

<div class="sf-notice"><strong>Availability notice:</strong> Formats depend on the source video. A private, deleted, age-restricted, region-restricted, live, or invalid link may not return a downloadable file. Only download content you own or have permission to use.</div>
<p><small>Page and workflow information updated: October 6, 2026.</small></p>

<h2>MP4 or WEBM video vs MP3 audio: which should you choose?</h2>
<div class="sf-table-wrap"><table><thead><tr><th>Option</th><th>Best for</th><th>Availability</th><th>Main trade-off</th></tr></thead><tbody><tr><td><strong>MP4 / WEBM video</strong></td><td>Watching picture and sound offline</td><td>SD, HD, Full HD, or higher when returned by the source</td><td>Higher resolution usually creates a larger file</td></tr><tr><td><strong>MP3 audio</strong></td><td>Music, podcasts, lectures, and voice content</td><td>Only the audio qualities listed in the results</td><td>No video picture is included</td></tr></tbody></table></div>

<h2>Download YouTube videos online</h2>
<p>Paste a complete public YouTube link into Save-Froms to view the formats currently available for that video. Choose the file type and quality that match your device, screen, and available storage, then use the download button shown beside that option.</p>
<p>The downloader works in a modern browser, so no account, app, browser extension, or additional desktop software is required.</p>

<h2>How to download a YouTube video</h2>
<ol class="sf-steps">
  <li><strong>Copy the video link</strong><span>Open the public video on YouTube, select Share, and copy its complete URL.</span></li>
  <li><strong>Paste the link</strong><span>Paste the copied URL into the Save-Froms download box above.</span></li>
  <li><strong>Choose format and quality</strong><span>Compare the available video or audio formats, resolution, bitrate, and file size.</span></li>
  <li><strong>Download the file</strong><span>Press Download beside your preferred option and save it to your phone or computer.</span></li>
</ol>

<h2>Video quality from SD to Full HD and above</h2>
<p>Available resolution is determined by the original YouTube upload and the formats returned for that link. A source may provide SD, HD, 1080p, or higher-resolution video choices. Some higher resolutions may use the WEBM container instead of MP4; Save-Froms labels the actual format so you know what you are downloading.</p>
<p>For smaller storage use, choose a lower resolution. For larger screens and sharper playback, select a higher quality when it is available.</p>

<h2>Easy to use on phones and computers</h2>
<p>Save-Froms works in modern browsers on Android, iPhone, iPad, Windows, and macOS. Its responsive interface lets you paste a link, compare formats, and start a download from either a mobile device or desktop computer.</p>

<h2>A fast browser-based YouTube downloader</h2>
<p>The workflow is intentionally simple: submit one valid public link, wait for the available source formats to appear, and choose the file that meets your needs. There are no complicated conversion settings or account-registration steps.</p>

<h2>Video and audio options in one result</h2>
<p>When available, Save-Froms displays video and audio choices in separate sections. Choose video when you need both picture and sound. Choose MP3 when you only need audio for music, lectures, interviews, or podcasts.</p>
<p>Comparing quality and estimated file size helps you choose a smaller file for limited storage or a higher-quality option for a larger display.</p>

<h2>Frequently asked questions</h2>
<div class="sf-faq">
  <details><summary>How do I download a YouTube video?</summary><p>Copy the complete URL of a public YouTube video, paste it into the downloader above, press Download, and choose one of the formats returned for that source.</p></details>
  <details><summary>Is Save-Froms free?</summary><p>The browser workflow can be used without creating an account. Your network provider or mobile carrier may still charge for data usage.</p></details>
  <details><summary>Can I download 1080p video?</summary><p>1080p appears when that quality is available from the source and returned by the media provider. It may be listed as MP4 or WEBM depending on the actual stream.</p></details>
  <details><summary>Does it work on mobile?</summary><p>Yes. Save-Froms is designed for modern mobile browsers on Android and iPhone as well as tablets and desktop computers.</p></details>
  <details><summary>Do I need to install an app?</summary><p>No separate app is required for the standard browser workflow. Paste the public link directly into the form above.</p></details>
  <details><summary>Why does a link sometimes return no formats?</summary><p>The video may be private, deleted, live, age-restricted, region-restricted, invalid, or temporarily unavailable from the media provider.</p></details>
</div>
HTML;

        DB::table('supported_sites')->where('slug', 'youtube-downloader')->update([
            'description' => $content,
            'meta_title' => 'YouTube Video Downloader – MP4, WEBM & MP3 | Save-Froms',
            'meta_description' => 'Download public YouTube videos in available MP4 or WEBM quality and save MP3 audio. Compare format, resolution, bitrate, and file size online.',
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('supported_sites')->where('slug', 'youtube-downloader')->update([
            'description' => 'Download public YouTube videos in available formats.',
            'meta_title' => 'YouTube Video Downloader',
            'meta_description' => 'Download public YouTube videos in available video and audio formats.',
            'updated_at' => now(),
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHomepageContentsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_seo_contents', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('intro')->nullable();
            $table->longText('content');
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 180)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('homepage_seo_contents')->insert([
            'heading' => 'Download Public Videos and Audio Online',
            'intro' => 'Save-Froms helps you check a complete public media link and compare the video or audio formats returned for that source. It works in a modern browser on phones, tablets and computers—without requiring an account.',
            'content' => <<<'HTML'
<h2>One browser downloader for popular media platforms</h2>
<p>Online media links are not all the same. A YouTube watch link, an Instagram Reel, a TikTok share URL and a Twitch clip can each return different formats and quality levels. Save-Froms checks the individual public link you provide, then displays the choices currently available from the media provider.</p>
<p>Start with our <a href="/youtube-downloader">YouTube downloader</a>, <a href="/instagram-downloader">Instagram downloader</a>, <a href="/tiktok-downloader">TikTok downloader</a>, <a href="/facebook-downloader">Facebook downloader</a> or browse the complete <a href="/supported-sites">supported sites directory</a>.</p>

<h2>How the online downloader works</h2>
<ol>
<li><strong>Copy one complete public URL.</strong> Open the individual video, Reel, Short, post or clip and copy its address.</li>
<li><strong>Paste the link above.</strong> Save-Froms checks the URL and asks the provider for available formats.</li>
<li><strong>Compare the results.</strong> Review format, resolution and estimated file size instead of assuming every link has the same quality.</li>
<li><strong>Choose Download.</strong> Save a format only when you own the media or have permission to use it.</li>
</ol>

<h2>Choose MP4, WEBM or MP3 based on your needs</h2>
<p><strong>MP4</strong> is a practical choice for video playback on most phones, tablets and computers. <strong>WEBM</strong> can offer efficient web video when the source provides it. <strong>MP3</strong> contains audio only and is useful for permitted lectures, podcasts or music sources. The exact choices are source-dependent; a resolution listed for one video may not appear for another.</p>
<table><thead><tr><th>Choice</th><th>Useful for</th><th>What to consider</th></tr></thead><tbody><tr><td>Lower resolution video</td><td>Quick sharing and limited storage</td><td>Smaller file, less detail on large screens</td></tr><tr><td>HD video</td><td>Offline viewing on larger displays</td><td>Larger file and more data usage</td></tr><tr><td>Audio only</td><td>Speech, lectures and permitted music</td><td>No video picture is included</td></tr></tbody></table>

<h2>Public links, availability and responsible use</h2>
<p>A private, deleted, age-restricted, region-restricted, live or invalid link may not return a downloadable file. Platforms can also change how their public pages and media formats work, so results can vary over time. If a link fails, confirm that it opens publicly in your browser, remove unnecessary tracking text and try the complete canonical URL.</p>
<p>Save-Froms is intended for public content that you created, own, or are allowed to save. It does not grant rights to copyrighted material and does not bypass account access or privacy controls. See the <a href="/download-troubleshooting">download troubleshooting guide</a> for common link problems and the <a href="/faq">FAQ</a> for quick answers.</p>

<h2>Works on mobile and desktop browsers</h2>
<p>The same paste-and-check workflow is available in modern browsers on Android, iPhone, iPad, Windows and macOS. You do not need to register for the browser workflow. On mobile, the downloaded file normally appears in the browser's Downloads area or the Files app; on desktop it usually appears in your selected downloads folder.</p>
HTML,
            'meta_title' => 'Free Online Video Downloader for Public Links | Save-Froms',
            'meta_description' => 'Paste a public media link, compare available MP4, WEBM or MP3 formats, and download permitted video or audio in your browser.',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('homepage_seo_contents');
    }
}

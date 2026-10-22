<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TargetThreePlatformSearchTerms extends Migration
{
    public function up()
    {
        $now = now();

        foreach ($this->pages() as $slug => $page) {
            $site = DB::table('supported_sites')->where('slug', $slug)->first();

            if (!$site) {
                continue;
            }

            $description = $site->description;
            if (strpos($description, $page['marker']) === false) {
                $description .= "\n".$page['section'];
            }

            DB::table('supported_sites')->where('id', $site->id)->update([
                'heading' => $page['heading'],
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'description' => $description,
                'updated_at' => $now,
            ]);
        }
    }

    private function pages()
    {
        return [
            'youtube-downloader' => [
                'heading' => '[YouTube Link] Downloader',
                'meta_title' => 'YouTube Link Downloader – MP4, WEBM & MP3 | Save-Froms',
                'meta_description' => 'Paste a public YouTube link to check available MP4, WEBM and MP3 formats. Compare quality and file size before saving authorised media.',
                'marker' => 'How does a YouTube link downloader work?',
                'section' => <<<'HTML'
<h2>How does a YouTube link downloader work?</h2>
<p>A YouTube link downloader needs the URL of one public video. A standard <code>youtube.com/watch?v=</code> address, a <code>youtu.be</code> share link and an individual <code>/shorts/</code> link can all identify the same video when they contain its video ID. Paste the complete public link above to view the formats currently returned for that source.</p>
<p>Do not paste a channel, playlist, search page or the general Shorts feed: those pages contain more than one item. The available MP4, WEBM and MP3 rows depend on the original upload and source delivery. Private, deleted, members-only, age-restricted and region-restricted videos are not available through a public-link workflow.</p>
HTML,
            ],
            'vimeo-downloader' => [
                'heading' => '[Download Vimeo Videos] Online',
                'meta_title' => 'Download Vimeo Videos – Public MP4 Link Guide | Save-Froms',
                'meta_description' => 'Download Vimeo videos from a public Vimeo link when available. Check creator privacy settings, returned MP4 quality and file size before saving.',
                'marker' => 'Download Vimeo videos from a public link',
                'section' => <<<'HTML'
<h2>Download Vimeo videos from a public link</h2>
<p>To download Vimeo videos, start with the individual public Vimeo video page rather than a profile, showcase or embedded player. Open the link in a private browser window first. If the video plays without a password, account or payment requirement, paste that public address above to check the MP4 files returned for the source.</p>
<p>Vimeo creators control privacy and download settings. A public player does not automatically grant permission to reuse the work, and a password-protected, private, domain-limited or removed video cannot be processed through a public-link tool. Compare the listed quality and size before saving material you own or are authorised to keep.</p>
HTML,
            ],
            'dailymotion-downloader' => [
                'heading' => '[Dailymotion to MP4] Downloader',
                'meta_title' => 'Dailymotion to MP4 – Public Video Downloader | Save-Froms',
                'meta_description' => 'Convert a public Dailymotion or dai.ly video link to an available MP4 format. Compare quality and file size before downloading authorised media.',
                'marker' => 'Dailymotion to MP4 from full and dai.ly links',
                'section' => <<<'HTML'
<h2>Dailymotion to MP4 from full and dai.ly links</h2>
<p>A Dailymotion to MP4 request works best with one public video URL. Both a full <code>dailymotion.com/video/</code> address and a <code>dai.ly</code> short link can identify the same item. If a short link opens the app first, open it in a browser and copy the final individual video address before pasting it above.</p>
<p>The MP4 qualities shown are source-dependent. The original upload, duration and Dailymotion's available renditions decide what can be returned; no downloader can create HD detail that the source did not provide. A private, deleted, age-restricted or region-limited video may not return a file. Download only videos you own or have permission to save.</p>
HTML,
            ],
        ];
    }

    public function down()
    {
        // SEO content updates are intentionally retained.
    }
}

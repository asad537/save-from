<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandInstagramVideoAndYoutubeMp4Terms extends Migration
{
    public function up()
    {
        $now = now();

        foreach ($this->updates() as $slug => $update) {
            $page = DB::table($update['table'])->where('slug', $slug)->first();
            if (!$page) {
                continue;
            }

            $content = $page->description ?? $page->content;
            if (strpos($content, $update['marker']) === false) {
                $content .= "\n".$update['section'];
            }

            $data = [
                'heading' => $update['heading'],
                'meta_title' => $update['meta_title'],
                'meta_description' => $update['meta_description'],
                'updated_at' => $now,
            ];
            $data[$update['content_column']] = $content;

            DB::table($update['table'])->where('id', $page->id)->update($data);
        }
    }

    private function updates()
    {
        return [
            'instagram-downloader' => [
                'table' => 'supported_sites',
                'content_column' => 'description',
                'heading' => '[Instagram Video Download] Tool',
                'meta_title' => 'Instagram Video Download – Public Videos & Reels | Save-Froms',
                'meta_description' => 'Use an Instagram video download tool for public Reels and video posts. Copy one public link, review the available format and save authorised media.',
                'marker' => 'Instagram video download: public Reels and video posts',
                'section' => <<<'HTML'
<h2>Instagram video download: public Reels and video posts</h2>
<p>An Instagram video download starts with one individual public video or Reel URL. Open the post itself, choose <strong>Share</strong>, and copy the link. Profile pages, hashtag results, Explore screens and photo-only posts do not identify one video source, so they cannot be checked as a single video download.</p>
<p>The available file depends on the public resources returned for that post. Quality and size can vary with the original upload and Instagram's processing. If the account is private, the post needs a login, the Reel was deleted or access is limited by region, a public-link workflow cannot retrieve it. Save only your own media or content you have permission to keep.</p>
HTML,
            ],
            'youtube-to-mp4' => [
                'table' => 'landing_pages',
                'content_column' => 'content',
                'heading' => '[YouTube MP4 Downloader] Online',
                'meta_title' => 'YouTube MP4 Downloader – Public Video Formats | Save-Froms',
                'meta_description' => 'Use a YouTube MP4 downloader for public video links. Compare available MP4 quality and file size before saving authorised media in your browser.',
                'marker' => 'YouTube MP4 downloader: use the public source URL',
                'section' => <<<'HTML'
<h2>YouTube MP4 downloader: use the public source URL</h2>
<p>A YouTube MP4 downloader works with one public watch, youtu.be or Shorts URL. Copy the link to the exact video, then compare the MP4 rows returned for that source. The same tool cannot use a channel page, a playlist, a search result or the general Shorts feed as if it were one video.</p>
<p>MP4 availability is source-dependent. A video may return several MP4 resolutions, one compatible file or a different container at higher quality. Higher resolutions can use separate video and audio streams, so preparation may take a moment. If a temporary result expires, begin again from the original public YouTube URL.</p>
HTML,
            ],
        ];
    }

    public function down()
    {
        // SEO content updates are intentionally retained.
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandDailymotionAndTiktokMp4Terms extends Migration
{
    public function up()
    {
        $now = now();

        $dailymotion = DB::table('supported_sites')->where('slug', 'dailymotion-downloader')->first();
        if ($dailymotion) {
            $description = $dailymotion->description;
            if (strpos($description, 'Dailymotion downloader: public full and dai.ly links') === false) {
                $description .= "\n".$this->dailymotionSection();
            }
            DB::table('supported_sites')->where('id', $dailymotion->id)->update([
                'heading' => '[Dailymotion Downloader] to MP4',
                'meta_title' => 'Dailymotion Downloader – Public Video to MP4 | Save-Froms',
                'meta_description' => 'Use a Dailymotion downloader for public dailymotion.com and dai.ly video links. Compare available MP4 quality and size before saving authorised media.',
                'description' => $description,
                'updated_at' => $now,
            ]);
        }

        $tiktok = DB::table('landing_pages')->where('slug', 'tiktok-mp4-downloader')->first();
        if ($tiktok) {
            $content = $tiktok->content;
            if (strpos($content, 'TikTok MP4: copy one public video link') === false) {
                $content .= "\n".$this->tiktokSection();
            }
            $faq = json_decode($tiktok->faq_items, true) ?: [];
            $addition = [
                'question' => 'Why is the TikTok MP4 file size different from another video at the same resolution?',
                'answer' => 'Duration, bitrate and source compression all affect the final size as well as resolution.',
            ];
            if (!collect($faq)->contains('question', $addition['question'])) {
                $faq[] = $addition;
            }
            DB::table('landing_pages')->where('id', $tiktok->id)->update([
                'title' => 'TikTok MP4 Downloader',
                'heading' => 'Public [TikTok MP4 Downloader]',
                'intro' => 'Paste one complete public TikTok video link to compare available MP4 quality and file size before saving media you are authorised to keep.',
                'meta_title' => 'TikTok MP4 Downloader – Public Video Link Tool | Save-Froms',
                'meta_description' => 'Use a TikTok MP4 downloader for public video links. Compare the available video quality and file size before saving authorised media in your browser.',
                'content' => $content,
                'faq_items' => json_encode($faq, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'updated_at' => $now,
            ]);
        }
    }

    private function dailymotionSection()
    {
        return <<<'HTML'
<h2>Dailymotion downloader: public full and dai.ly links</h2>
<p>A Dailymotion downloader checks the URL for one individual public video. Both a full <code>dailymotion.com/video/</code> link and a <code>dai.ly</code> share link can work when they open the same item. If a short link opens an app first, use a browser to complete the redirect and copy the final video address.</p>
<p>A profile, channel, category or search page contains more than one item and cannot be processed as one video. MP4 quality and size are determined by the source video and its available renditions. Private, deleted, login-restricted, age-restricted or region-limited sources may not return a download option.</p>
HTML;
    }

    private function tiktokSection()
    {
        return <<<'HTML'
<h2>TikTok MP4: copy one public video link</h2>
<p>A TikTok MP4 downloader needs the link to one individual public video. Use TikTok's Share menu to copy the link, then open it in a private browser window to confirm it is publicly available. A profile, hashtag, sound page or For You feed has many posts and does not identify one MP4 source.</p>
<p>The MP4 results shown for a post are source-dependent. Resolution, duration, bitrate and TikTok's delivery format all affect the available choices and file size. If a temporary result expires, start again from the original public TikTok video URL. Download only videos you created or media you have permission to keep.</p>
HTML;
    }

    public function down()
    {
        // SEO content updates are intentionally retained.
    }
}

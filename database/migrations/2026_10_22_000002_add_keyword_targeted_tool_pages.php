<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddKeywordTargetedToolPages extends Migration
{
    public function up()
    {
        $now = now();

        foreach ($this->pages() as $page) {
            DB::table('landing_pages')->updateOrInsert(
                ['slug' => $page['slug']],
                $page + ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    private function pages()
    {
        return [
            [
                'title' => 'YouTube Link Downloader',
                'slug' => 'youtube-link-downloader',
                'eyebrow' => 'YouTube Link Tool',
                'heading' => 'Use a [YouTube Link] Downloader',
                'intro' => 'Paste one complete public YouTube watch, youtu.be or Shorts link to check the video and audio formats currently available for that source.',
                'meta_title' => 'YouTube Link Downloader – Public Video Formats | Save-Froms',
                'meta_description' => 'Use a public YouTube link downloader to check available MP4, WEBM and MP3 formats. Copy one video URL, compare quality and file size, then save authorised media.',
                'content' => <<<'HTML'
<h2>Paste one public YouTube link</h2>
<p>A YouTube link downloader works with the address of one individual public video. You can copy a normal <code>youtube.com/watch?v=</code> URL, a <code>youtu.be</code> share link or an individual <code>/shorts/</code> link. Each can identify the same media item when the video ID matches.</p>
<div class="tool-steps"><div><strong>Open one video</strong><span>Use an individual public video or Short.</span></div><div><strong>Copy its link</strong><span>Use YouTube's Share menu or the address bar.</span></div><div><strong>Check formats</strong><span>Paste the complete URL into the form above.</span></div><div><strong>Choose a file</strong><span>Compare the returned format, quality and size.</span></div></div>
<p>A channel, playlist, search result or the general Shorts feed does not identify one source. Private, deleted, members-only, age-restricted and region-restricted videos are unavailable to a public-link workflow.</p>
<h2>Choose the format that fits your device</h2>
<p>MP4 is usually the most compatible choice for video with sound. WEBM can be useful in current browsers and some media players. MP3 is audio-only and appears only when the source returns an audio option. The list on the result page is the source of truth for that one public URL.</p>
<div class="note"><strong>Permission matters:</strong> download only videos you own, public-domain media or content you are authorised to keep. Public access does not grant permission to repost or reuse a creator's work.</div>
<h2>Frequently asked questions</h2>
<h3>Can I use a youtu.be link?</h3><p>Yes. A youtu.be share link can work when it resolves to one public YouTube video.</p>
<h3>Why is there no format for my YouTube link?</h3><p>The URL may point to a collection instead of one video, or the source may be private, restricted, deleted or unavailable.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Does a YouTube link downloader require my YouTube login?', 'answer' => 'No. A public-link workflow should not require account credentials.'],
                    ['question' => 'Why did a download URL expire?', 'answer' => 'Media delivery links are temporary. Paste the original public video link again for a fresh result.'],
                ],
                'is_active' => 1,
                'sort_order' => 20,
            ],
            [
                'title' => 'Download Vimeo Videos',
                'slug' => 'download-vimeo-videos',
                'eyebrow' => 'Vimeo Video Tool',
                'heading' => '[Download Vimeo Videos] Online',
                'intro' => 'Paste the URL of one public Vimeo video to check whether a compatible MP4 file and quality are available for that source.',
                'meta_title' => 'Download Vimeo Videos – Public MP4 Link Tool | Save-Froms',
                'meta_description' => 'Download Vimeo videos from a public Vimeo link when an MP4 file is available. Check creator privacy settings, quality and file size before saving authorised media.',
                'content' => <<<'HTML'
<h2>Start with the individual public Vimeo video</h2>
<p>To download Vimeo videos, copy the URL of the video page itself—not a creator profile, a showcase or an embedded player. Before pasting it here, test the link in a private browser window. A link that asks for a password, account, payment or access through a particular website is not a public source.</p>
<div class="tool-steps"><div><strong>Open the video page</strong><span>Select one Vimeo video rather than a collection.</span></div><div><strong>Copy the public URL</strong><span>Use the Share menu or the browser address bar.</span></div><div><strong>Review results</strong><span>Check the MP4 format, quality and estimated size.</span></div><div><strong>Save permitted media</strong><span>Choose only a file you are allowed to keep.</span></div></div>
<h2>Vimeo privacy and quality limits</h2>
<p>Vimeo creators control audience and download settings. A public player does not necessarily mean a file is available, and a downloader cannot remove a password, bypass a private link or override a creator's distribution choice. The qualities returned depend on the original upload and source delivery.</p>
<div class="note"><strong>Respect creator rights:</strong> public playback is not a licence to republish, edit or distribute someone else's video. Keep only material you own or are authorised to use.</div>
<h2>Frequently asked questions</h2>
<h3>Can I download a password-protected Vimeo video?</h3><p>No. Password-protected and login-restricted videos are not public sources.</p>
<h3>Why is only one Vimeo quality listed?</h3><p>The available options are determined by the media resources returned for that specific public video.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Can I download Vimeo videos from a private account?', 'answer' => 'No. Private and access-restricted Vimeo content is outside a public-link workflow.'],
                    ['question' => 'Where does a Vimeo download save?', 'answer' => 'Your browser controls the location, usually its Downloads area or the Files app on a phone.'],
                ],
                'is_active' => 1,
                'sort_order' => 21,
            ],
            [
                'title' => 'Dailymotion to MP4',
                'slug' => 'dailymotion-to-mp4',
                'eyebrow' => 'Dailymotion MP4 Tool',
                'heading' => 'Convert [Dailymotion to MP4]',
                'intro' => 'Paste one public Dailymotion or dai.ly link to see which MP4 resolution and file size the source currently returns.',
                'meta_title' => 'Dailymotion to MP4 – Public Video Link Tool | Save-Froms',
                'meta_description' => 'Convert a public Dailymotion or dai.ly link to an available MP4 format. Compare source-dependent quality and file size before downloading authorised media.',
                'content' => <<<'HTML'
<h2>Use a full Dailymotion or dai.ly link</h2>
<p>Dailymotion to MP4 works with the URL of one public video. A full <code>dailymotion.com/video/</code> address and a <code>dai.ly</code> short link can point to the same source. If a share link opens an app, open it in a browser first and copy the final individual video URL.</p>
<div class="tool-steps"><div><strong>Open one public video</strong><span>Do not use a channel, search or category page.</span></div><div><strong>Copy its URL</strong><span>Use the Dailymotion Share option.</span></div><div><strong>Check MP4 formats</strong><span>Paste the link into the form above.</span></div><div><strong>Choose quality</strong><span>Compare the displayed resolution and size.</span></div></div>
<h2>MP4 quality is source-dependent</h2>
<p>The original upload and Dailymotion's available renditions decide which MP4 rows can be returned. A higher resolution normally uses more storage, while a compact file can be practical for a phone or limited connection. A downloader cannot create HD detail that was not part of the source video.</p>
<p>Private, deleted, age-restricted and region-limited videos may not return a file. If a temporary download link returns an error, start again with the original public Dailymotion URL to generate a fresh result.</p>
<div class="note"><strong>Authorised use only:</strong> save videos you own, have licensed or have clear permission to keep. Public visibility does not automatically permit reuse.</div>
<h2>Frequently asked questions</h2>
<h3>Do dai.ly links work?</h3><p>They can work when they resolve to one public Dailymotion video.</p>
<h3>Why is 1080p missing?</h3><p>The available resolutions depend on the original upload and media resources returned for that particular video.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Can I convert a private Dailymotion video to MP4?', 'answer' => 'No. A public-link workflow cannot access private or login-restricted media.'],
                    ['question' => 'Why did my Dailymotion MP4 link expire?', 'answer' => 'Temporary media URLs expire. Return to the original public video link and request a fresh result.'],
                ],
                'is_active' => 1,
                'sort_order' => 22,
            ],
        ];
    }

    public function down()
    {
        DB::table('landing_pages')->whereIn('slug', [
            'youtube-link-downloader',
            'download-vimeo-videos',
            'dailymotion-to-mp4',
        ])->delete();
    }
}

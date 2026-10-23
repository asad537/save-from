<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFacebookReelsPageAndExpandYoutubeShorts extends Migration
{
    public function up()
    {
        $now = now();

        DB::table('landing_pages')->updateOrInsert(
            ['slug' => 'facebook-reels-downloader'],
            $this->facebookReelsPage() + ['created_at' => $now, 'updated_at' => $now]
        );

        $shorts = DB::table('landing_pages')->where('slug', 'youtube-shorts-downloader')->first();
        if (!$shorts) {
            return;
        }

        $content = $shorts->content;
        if (strpos($content, 'YouTube short download: choose the individual Short') === false) {
            $content .= "\n".$this->youtubeShortsSection();
        }

        $faq = json_decode($shorts->faq_items, true) ?: [];
        $addition = [
            'question' => 'Can I use a youtu.be link for a YouTube Short download?',
            'answer' => 'Yes. A youtu.be link can work when it resolves to one public Short with a valid video ID.',
        ];
        if (!collect($faq)->contains('question', $addition['question'])) {
            $faq[] = $addition;
        }

        DB::table('landing_pages')->where('id', $shorts->id)->update([
            'title' => 'YouTube Short Downloader',
            'heading' => 'YouTube [Short Download] Tool',
            'intro' => 'Paste one complete public YouTube Short link to compare the available video formats, portrait quality and file size before saving authorised media.',
            'meta_title' => 'YouTube Short Download – Public Shorts Video Tool | Save-Froms',
            'meta_description' => 'Use a YouTube Short download tool for public Shorts links. Copy one individual Short URL, compare available quality and file size, then save authorised media.',
            'content' => $content,
            'faq_items' => json_encode($faq, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'updated_at' => $now,
        ]);
    }

    private function facebookReelsPage()
    {
        return [
            'title' => 'Facebook Reels Downloader',
            'slug' => 'facebook-reels-downloader',
            'eyebrow' => 'Facebook Reels Tool',
            'heading' => '[Download Facebook Reels] Online',
            'intro' => 'Paste one complete public Facebook Reel link to check the video format and file size currently available for that source.',
            'meta_title' => 'Download Facebook Reels – Public Reel Video Tool | Save-Froms',
            'meta_description' => 'Download Facebook Reels from a public Reel link when a compatible video file is available. Check the returned format and file size before saving authorised media.',
            'content' => <<<'HTML'
<h2>Download Facebook Reels from an individual public link</h2>
<p>To download Facebook Reels, open the individual Reel and use its <strong>Share</strong> menu to copy the link. A Facebook profile, Page timeline, Watch feed or search result can contain many posts, so it does not identify the one video you want to check. The individual Reel URL is the useful starting point.</p>
<div class="tool-steps"><div><strong>Open one Reel</strong><span>Choose an individual Reel that is publicly visible.</span></div><div><strong>Copy its link</strong><span>Use Share and select Copy link.</span></div><div><strong>Check available formats</strong><span>Paste the full URL into the form above.</span></div><div><strong>Save permitted media</strong><span>Choose from the returned video options.</span></div></div>
<h2>Public Reels and restricted Facebook content</h2>
<p>A Reel may play in your Facebook app because you are signed in, yet still be unavailable to visitors without your session. Test the copied link in a private browser window. If it needs a login, has a limited audience, was deleted or is region-restricted, a public-link workflow cannot access it.</p>
<h2>Quality, size and phone downloads</h2>
<p>The available result depends on Facebook's source delivery for that Reel. The original upload and its processing decide the formats and quality that can appear. On Android, files normally appear in the browser Downloads area. On iPhone, Safari generally saves to Files → Downloads. Check the actual listed size before choosing a larger quality on a limited connection.</p>
<div class="note"><strong>Creator rights apply:</strong> a public Reel is not automatically free to repost or edit. Download only your own Reels, licensed assets or content the creator has authorised you to keep.</div>
<h2>Frequently asked questions</h2>
<h3>Can I download Facebook Reels from a private profile?</h3><p>No. Private and login-restricted Reels are not public sources.</p>
<h3>Why does a Facebook Reel result have one quality only?</h3><p>The formats returned depend on that individual source. A downloader cannot create a higher-quality version that Facebook did not provide.</p>
HTML,
            'faq_items' => json_encode([
                ['question' => 'Can I use an fb.watch link for a Facebook Reel?', 'answer' => 'It can work when it resolves to one public Facebook Reel or video.'],
                ['question' => 'Why did my Facebook Reel download link expire?', 'answer' => 'Media URLs are temporary. Return to the original public Reel link to create a fresh result.'],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'is_active' => 1,
            'sort_order' => 23,
        ];
    }

    private function youtubeShortsSection()
    {
        return <<<'HTML'
<h2>YouTube short download: choose the individual Short</h2>
<p>A YouTube short download begins with one public Short URL. Copy the link from the individual Short, not the general Shorts feed, a creator's Shorts tab or a playlist. A <code>youtube.com/shorts/</code> address, a matching <code>youtu.be</code> link and a normal watch URL can all identify the same Short when they carry the same video ID.</p>
<p>Shorts are usually portrait videos. The result page lists only the formats and quality available for that source, so an older or compressed Short may have fewer choices. Choose a compact option for phone storage or a higher listed option when it offers a useful improvement on your screen. Private, deleted, members-only, age-restricted and region-limited Shorts are not available through a public-link workflow.</p>
HTML;
    }

    public function down()
    {
        DB::table('landing_pages')->where('slug', 'facebook-reels-downloader')->delete();
    }
}

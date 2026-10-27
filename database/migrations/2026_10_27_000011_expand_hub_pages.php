<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The two site-wide help hubs were the last tool pages under 600 words.
 * Each gains page-specific sections (error table, device checks, link
 * patterns) and six FAQs.
 */
class ExpandHubPages extends Migration
{
    public function up()
    {
        $now = now();
        foreach ($this->pages() as $slug => $page) {
            $row = DB::table('landing_pages')->where('slug', $slug)->first();
            if (!$row || strpos($row->content, $page['marker']) !== false) {
                continue;
            }
            DB::table('landing_pages')->where('id', $row->id)->update([
                'content' => $row->content."\n".$page['section'],
                'faq_items' => json_encode($page['faq_items'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'updated_at' => $now,
            ]);
        }
    }

    private function pages()
    {
        return [
            'download-troubleshooting' => [
                'marker' => 'Every message the downloader can show',
                'meta_title' => 'Video Download Not Working? Fixes for Every Error | Save-Froms',
                'meta_description' => 'Fix a video download that is not working: what each error means, private and expired links, Preparing that never finishes, 403 files, and browser or phone problems.',
                'section' => <<<'HTML'
<h2>Every message the downloader can show, and what to do</h2>
<table><thead><tr><th>Message</th><th>Where it appears</th><th>Cause</th><th>Fix</th></tr></thead><tbody>
<tr><td>Please enter a valid public URL</td><td>Under the box, right after submitting</td><td>The text is not a web address: a search term, a username, or a link with spaces</td><td>Paste the full address starting with https://</td></tr>
<tr><td>This platform is not supported or is currently disabled</td><td>Under the box</td><td>The domain is not one of the <a href="/supported-sites">supported sites</a>, or the platform is switched off</td><td>Check the platform list; for a video embedded on another website, find the original platform link</td></tr>
<tr><td>The media provider could not process this link right now</td><td>Under the box, after a few seconds</td><td>The link opens a profile, playlist, feed, private post or deleted video, or the provider is busy</td><td>Open the link in a private window; if it plays without login, wait a minute and submit again</td></tr>
<tr><td>Preparing… that never finishes</td><td>After pressing Download on a high-quality row</td><td>Video and audio are being joined for 1080p and above; long videos take longer</td><td>Wait up to two minutes; if it stops, choose 720p or submit the link again</td></tr>
<tr><td>This download request has expired</td><td>On a new page after pressing Download</td><td>More than 20 minutes passed since the result was generated</td><td>Submit the link again and download from the fresh result</td></tr>
<tr><td>This quality is temporarily unavailable</td><td>After Preparing</td><td>The provider could not fetch that rendition at that moment</td><td>Pick another row, or try the same row again in a few minutes</td></tr>
<tr><td>403 Forbidden on the file</td><td>In the browser's download list</td><td>The temporary file address was reused after it expired</td><td>Never retry an old file link; go back to the result or resubmit the link</td></tr>
<tr><td>A tiny file that will not play</td><td>After the download "finishes"</td><td>An error page was saved instead of the video</td><td>Delete it, resubmit the link, and download again on Wi-Fi</td></tr>
</tbody></table>
<h2>Links that look right but are not single videos</h2>
<p>Most failed requests are addresses that point at a collection instead of one item. The patterns below never return a file, whatever the platform: a profile or channel (<code>youtube.com/@name</code>, <code>instagram.com/name/</code>, <code>tiktok.com/@name</code>), a playlist or board (<code>list=</code>, <code>/playlist/</code>, <code>/boards/</code>), a hashtag, sound or topic page, a search results page, a feed such as Shorts, Reels or For You, and a live stream that is still live. Open the item itself, copy its own link from the Share menu, and the result changes from an error to a list of files.</p>
<h2>Phone-specific problems</h2>
<ul>
<li><strong>Nothing happens after Download in the Instagram, Facebook or TikTok app:</strong> the in-app browser blocks file downloads. Copy the link, open Chrome or Safari, and paste it there.</li>
<li><strong>The file is not in the gallery:</strong> Android saves to Downloads and the gallery indexes it a moment later; iPhone saves to Files → Downloads, from where Share → Save Video moves it to Photos.</li>
<li><strong>Download stops at a few per cent:</strong> mobile data dropped or the phone went to sleep. Keep the screen on for large files or use Wi-Fi.</li>
<li><strong>Safari asks "Do you want to download?" and nothing follows:</strong> tap Download in that prompt; the arrow icon in the address bar shows progress.</li>
<li><strong>Content blocker or VPN:</strong> some blockers stop the provider's file hosts. Pause the blocker for this site and retry.</li>
</ul>
<h2>Desktop-specific problems</h2>
<ul>
<li><strong>Chrome shows "Insecure download blocked":</strong> the file came over HTTP from a provider host. Resubmit the link; a fresh result uses HTTPS addresses.</li>
<li><strong>The browser opens the video instead of saving it:</strong> right-click the Download button and choose "Save link as", or let it open and use the player's save option.</li>
<li><strong>An antivirus deletes the file:</strong> a heuristic false positive on a large MP4 is common; restore it from quarantine or whitelist the Downloads folder.</li>
<li><strong>Firefox saves a .part file forever:</strong> the download stalled; cancel, resubmit the link and download again.</li>
</ul>
<h2>When the platform, not the link, is the problem</h2>
<p>Platforms change their pages without notice, and a provider needs time to adapt. If every link from one platform fails on the same day while others work, that is a platform-side change. Try again later rather than reinstalling browsers or clearing data. The platform pages on this site are updated when a change is confirmed, and the date at the bottom of each page shows the last review.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Why does a video play on the platform but return no formats here?', 'answer' => 'Playing while you are signed in is not the same as being public. Open the link in a private window; if it asks you to log in, join a group or shows "not available", it is not public and no public-link tool can read it.'],
                    ['question' => 'How long is a result valid?', 'answer' => 'Twenty minutes. After that, Download links show the expired message and you need to submit the link again.'],
                    ['question' => 'Why does 1080p take longer than 720p?', 'answer' => 'Platforms such as YouTube store 1080p and above as video-only streams, so the audio has to be joined before the file is ready.'],
                    ['question' => 'Does clearing cookies or cache help?', 'answer' => 'Rarely. Almost every failure is the link, the privacy setting or an expired result. Clearing data only helps when the Download button does nothing at all in one browser but works in another.'],
                    ['question' => 'Can I report a link that should work?', 'answer' => 'Yes. Use the contact page with the link and the message you saw; links that reveal a platform change help every visitor.'],
                    ['question' => 'Why is only one quality listed?', 'answer' => 'The source only has that rendition. The result shows what the platform returns for that video; nothing is hidden behind a paid option.'],
                ],
            ],
            'how-to-download-videos' => [
                'marker' => 'The same four steps on every platform',
                'meta_title' => 'How to Download Videos Online from a Link | Save-Froms',
                'meta_description' => 'How to download videos online from a public link on any platform: copy the right link, check the formats returned, pick a quality, and find the file on Android, iPhone or desktop.',
                'section' => <<<'HTML'
<h2>The same four steps on every platform, with the link that works</h2>
<table><thead><tr><th>Platform</th><th>Copy this link</th><th>Expect</th><th>Dedicated page</th></tr></thead><tbody>
<tr><td>YouTube</td><td>watch, youtu.be or Shorts link from Share</td><td>MP4 up to 1080p, WEBM above that, MP3 audio rows</td><td><a href="/youtube-downloader">YouTube video downloader</a></td></tr>
<tr><td>Instagram</td><td>Reel or post link from the paper-plane menu</td><td>One MP4 at 720p or 1080p</td><td><a href="/instagram-downloader">Instagram video downloader</a></td></tr>
<tr><td>TikTok</td><td>Share → Copy link (vm/vt short link is fine)</td><td>MP4, usually without the overlay, plus the sound</td><td><a href="/tiktok-downloader">TikTok video downloader</a></td></tr>
<tr><td>Facebook</td><td>Share → Copy link on the video or Reel</td><td>SD and HD MP4 when both exist</td><td><a href="/facebook-downloader">Facebook video downloader</a></td></tr>
<tr><td>X / Twitter</td><td>The post's status link</td><td>Up to three MP4 bitrates</td><td><a href="/twitter-downloader">X video downloader</a></td></tr>
<tr><td>Vimeo</td><td>The video page link, with its hash if unlisted</td><td>MP4 at every rendition the creator allows</td><td><a href="/vimeo-downloader">Vimeo video downloader</a></td></tr>
<tr><td>Dailymotion</td><td>/video/x… or dai.ly link</td><td>MP4 from 240p to 1080p</td><td><a href="/dailymotion-downloader">Dailymotion video downloader</a></td></tr>
<tr><td>Twitch</td><td>Clip or /videos/ link</td><td>MP4 clips and VODs</td><td><a href="/twitch-downloader">Twitch clip downloader</a></td></tr>
<tr><td>Threads</td><td>The post link</td><td>One 720p MP4</td><td><a href="/threads-downloader">Threads video downloader</a></td></tr>
</tbody></table>
<h2>Reading the result page</h2>
<p>Every result has the same shape: the video's thumbnail and title on the left, then a Video section and, when the source offers it, an Audio section. Each row shows three things: the container badge (MP4, WEBM or MP3), the quality (a resolution such as 720P or a bitrate such as 128KBPS) and the estimated size. Rows are ordered from smallest to largest. A Download button that first shows "Preparing" means the provider is joining video and audio for that row; it is normal for 1080p and above and takes longer on long videos.</p>
<h2>Which row to pick</h2>
<table><thead><tr><th>You will</th><th>Pick</th><th>Why</th></tr></thead><tbody>
<tr><td>Watch on a phone</td><td>720p MP4</td><td>Looks sharp on a phone screen at a quarter of the 1080p size</td></tr>
<tr><td>Watch on a laptop or TV</td><td>1080p MP4</td><td>Full HD on a big screen; WEBM only if your player supports it</td></tr>
<tr><td>Edit the video</td><td>The largest MP4 row</td><td>More detail survives cropping and re-encoding</td></tr>
<tr><td>Send through WhatsApp or Messenger</td><td>480p or 720p</td><td>The app compresses it again anyway and may cap the size</td></tr>
<tr><td>Only listen</td><td>The 128 kbps audio row</td><td>A twentieth of the video's size; speech sounds identical to 256 kbps</td></tr>
<tr><td>Keep an archive copy</td><td>The largest row, WEBM included</td><td>Highest quality the platform holds; convert later if needed</td></tr>
</tbody></table>
<h2>Before you download: the permission question</h2>
<p>A public video is not a free video. Download a file when it is yours, when the creator has allowed downloads, or when the licence covers your use, such as a Creative Commons film or a clip you were sent for a project. Keep the original link with the file so you can credit the creator later. Private videos never appear here, because the downloader reads only what anyone could see without logging in and never uses your account.</p>
<h2>Saving a video you were sent</h2>
<p>A video that arrives in WhatsApp, Messenger or email is already a file on your phone; long-press it and choose Save or Download, and no link tool is needed. A link that arrives the same way is a different case: open it once to see which platform it belongs to, confirm it plays without signing in, then paste it here. Shortened links such as youtu.be, vm.tiktok.com and fb.watch are fine to paste as they are.</p>
<h2>Downloading several videos</h2>
<p>There is no batch mode, and a playlist or profile link returns nothing. The quickest way to save a series is on a desktop browser: open each video, copy its link, paste it, press Download, and move to the next while the first file arrives. Results stay valid for 20 minutes, so you can queue a handful of downloads from one sitting without resubmitting links.</p>
HTML,
                'faq_items' => [
                    ['question' => 'Do I need an account or an app to download videos?', 'answer' => 'No. Everything runs in your browser. A site that asks you to install an app or extension to download a video is adding risk, not features.'],
                    ['question' => 'Which link do I copy?', 'answer' => 'The link of the video itself, from the platform\'s Share menu or the address bar. Profile, playlist, feed and search links contain many videos and return nothing.'],
                    ['question' => 'Where does the file go?', 'answer' => 'Downloads on Android and desktop; Files → Downloads on iPhone, from where Share → Save Video moves it to Photos.'],
                    ['question' => 'Why is 4K or 1080p missing for my video?', 'answer' => 'The upload was made at a lower resolution. The result lists what the platform holds; no downloader can add resolution that was never there.'],
                    ['question' => 'Can I download a private or friends-only video?', 'answer' => 'No. The downloader reads only public links and never uses your login.'],
                    ['question' => 'Is there a limit on downloads?', 'answer' => 'Ordinary use is unlimited. Requests are rate-limited per minute to keep the service responsive for everyone.'],
                ],
            ],
        ];
    }

    public function down()
    {
        // Content expansions are forward-only.
    }
}
